<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import ResizeImage from 'tiptap-extension-resize-image';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import TextAlign from '@tiptap/extension-text-align';
import { TextStyle } from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import { Highlight } from '@tiptap/extension-highlight';
import { Table } from '@tiptap/extension-table';
import { TableRow } from '@tiptap/extension-table-row';
import { TableHeader } from '@tiptap/extension-table-header';
import { TableCell } from '@tiptap/extension-table-cell';
import YouTube from '@tiptap/extension-youtube';
import {
    Bold,
    Italic,
    List,
    ListOrdered,
    UnderlineIcon,
    Strikethrough,
    AlignLeft,
    AlignCenter,
    AlignRight,
    LinkIcon,
    ImageIcon,
    Youtube,
    Quote,
    Minus,
    Trash2,
    Undo,
    Redo,
    Table as TableIcon,
    Grid3x3,
    Type,
    Highlighter,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { cn } from '@/lib/utils';

interface Props {
    modelValue: string;
    placeholder?: string;
    error?: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [1, 2, 3, 4, 5, 6],
            },
            link: false,
            underline: false,
        }),
        Placeholder.configure({
            placeholder: props.placeholder ?? '',
        }),
        ResizeImage.configure({
            allowBase64: true,
            inline: true,
        }),
        Link.configure({
            openOnClick: false,
        }),
        Underline,
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        TextStyle,
        Color,
        Highlight.configure({
            multicolor: true,
        }),
        Table.configure({
            resizable: true,
        }),
        TableRow,
        TableHeader,
        TableCell,
        YouTube.configure({
            width: 640,
            height: 480,
        }),
    ],
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && editor.value.getHTML() !== value) {
            editor.value.commands.setContent(value, { emitUpdate: false });
        }
    },
);

const linkDialogOpen = ref(false);
const linkUrl = ref('');
const imageInputRef = ref<HTMLInputElement | null>(null);
const tableDialogOpen = ref(false);
const tableRows = ref(3);
const tableCols = ref(3);
const youtubeDialogOpen = ref(false);
const youtubeUrl = ref('');
const textColorInputRef = ref<HTMLInputElement | null>(null);
const highlightColorInputRef = ref<HTMLInputElement | null>(null);

function openLinkDialog(): void {
    if (!editor.value) {
        return;
    }

    const previousUrl = editor.value.getAttributes('link').href;
    linkUrl.value = previousUrl ?? '';
    linkDialogOpen.value = true;
}

function setLink(): void {
    if (!editor.value) {
        return;
    }

    if (linkUrl.value === '') {
        editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
    } else {
        editor.value
            .chain()
            .focus()
            .extendMarkRange('link')
            .setLink({ href: linkUrl.value })
            .run();
    }

    linkDialogOpen.value = false;
    linkUrl.value = '';
}

function handleImageUpload(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file || !editor.value) {
        return;
    }

    const reader = new FileReader();
    reader.onload = () => {
        const result = reader.result as string;
        editor.value?.chain().focus().setImage({ src: result }).run();
    };
    reader.readAsDataURL(file);

    (event.target as HTMLInputElement).value = '';
}

function insertTable(): void {
    if (!editor.value) {
        return;
    }

    editor.value
        .chain()
        .focus()
        .insertTable({
            rows: tableRows.value,
            cols: tableCols.value,
            withHeaderRow: true,
        })
        .run();

    tableDialogOpen.value = false;
    tableRows.value = 3;
    tableCols.value = 3;
}

function insertYouTube(): void {
    if (!editor.value || !youtubeUrl.value) {
        return;
    }

    editor.value
        .chain()
        .focus()
        .setYoutubeVideo({
            src: youtubeUrl.value,
        })
        .run();

    youtubeDialogOpen.value = false;
    youtubeUrl.value = '';
}

function setTextColor(event: Event): void {
    const color = (event.target as HTMLInputElement).value;
    editor.value?.chain().focus().setColor(color).run();
}

function setHighlightColor(event: Event): void {
    const color = (event.target as HTMLInputElement).value;
    editor.value?.chain().focus().toggleHighlight({ color }).run();
}

function unsetTextColor(): void {
    editor.value?.chain().focus().unsetColor().run();
}

function unsetHighlight(): void {
    editor.value?.chain().focus().unsetHighlight().run();
}

const currentBlockType = computed(() => {
    if (!editor.value) {
        return 'paragraph';
    }

    if (editor.value.isActive('heading', { level: 1 })) {
        return 'heading1';
    }
    if (editor.value.isActive('heading', { level: 2 })) {
        return 'heading2';
    }
    if (editor.value.isActive('heading', { level: 3 })) {
        return 'heading3';
    }
    if (editor.value.isActive('heading', { level: 4 })) {
        return 'heading4';
    }
    if (editor.value.isActive('heading', { level: 5 })) {
        return 'heading5';
    }
    if (editor.value.isActive('heading', { level: 6 })) {
        return 'heading6';
    }

    return 'paragraph';
});

function setBlockType(value: string): void {
    if (!editor.value) {
        return;
    }

    if (value === 'paragraph') {
        editor.value.chain().focus().setParagraph().run();
    } else {
        const level = parseInt(value.replace('heading', ''), 10) as 1 | 2 | 3 | 4 | 5 | 6;
        editor.value.chain().focus().toggleHeading({ level }).run();
    }
}
</script>

<template>
    <div
        :class="cn(
            'rounded-md border border-input bg-transparent shadow-xs transition-[color,box-shadow] focus-within:border-ring focus-within:ring-[3px] focus-within:ring-ring/50',
            error && 'border-destructive ring-destructive/20',
        )"
    >
        <div class="flex flex-wrap items-center gap-1 border-b border-input bg-muted/50 p-1.5">
            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Bold"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('bold') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleBold().run()"
                >
                    <Bold class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Italic"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('italic') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleItalic().run()"
                >
                    <Italic class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Underline"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('underline') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleUnderline().run()"
                >
                    <UnderlineIcon class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Strikethrough"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('strike') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleStrike().run()"
                >
                    <Strikethrough class="size-4" />
                </Button>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Text Color"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('textStyle') && 'bg-accent text-accent-foreground',
                    )"
                    @click="textColorInputRef?.click()"
                >
                    <Type class="size-4" />
                </Button>
                <input
                    ref="textColorInputRef"
                    type="color"
                    class="hidden"
                    @change="setTextColor"
                />
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Remove Color"
                    class="h-8 w-8"
                    @click="unsetTextColor"
                >
                    <Type class="size-4 text-muted-foreground" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Highlight"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('highlight') && 'bg-accent text-accent-foreground',
                    )"
                    @click="highlightColorInputRef?.click()"
                >
                    <Highlighter class="size-4" />
                </Button>
                <input
                    ref="highlightColorInputRef"
                    type="color"
                    value="#fef08a"
                    class="hidden"
                    @change="setHighlightColor"
                />
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Remove Highlight"
                    class="h-8 w-8"
                    @click="unsetHighlight"
                >
                    <Highlighter class="size-4 text-muted-foreground" />
                </Button>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Align Left"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive({ textAlign: 'left' }) && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().setTextAlign('left').run()"
                >
                    <AlignLeft class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Align Center"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive({ textAlign: 'center' }) && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().setTextAlign('center').run()"
                >
                    <AlignCenter class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Align Right"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive({ textAlign: 'right' }) && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().setTextAlign('right').run()"
                >
                    <AlignRight class="size-4" />
                </Button>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Select
                    :model-value="currentBlockType"
                    @update:model-value="setBlockType"
                >
                    <SelectTrigger class="h-8 w-32 text-xs">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="paragraph">Paragraph</SelectItem>
                        <SelectItem value="heading1">Heading 1</SelectItem>
                        <SelectItem value="heading2">Heading 2</SelectItem>
                        <SelectItem value="heading3">Heading 3</SelectItem>
                        <SelectItem value="heading4">Heading 4</SelectItem>
                        <SelectItem value="heading5">Heading 5</SelectItem>
                        <SelectItem value="heading6">Heading 6</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Bullet List"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('bulletList') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleBulletList().run()"
                >
                    <List class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Numbered List"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('orderedList') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleOrderedList().run()"
                >
                    <ListOrdered class="size-4" />
                </Button>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Blockquote"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('blockquote') && 'bg-accent text-accent-foreground',
                    )"
                    @click="editor?.chain().focus().toggleBlockquote().run()"
                >
                    <Quote class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Horizontal Rule"
                    class="h-8 w-8"
                    @click="editor?.chain().focus().setHorizontalRule().run()"
                >
                    <Minus class="size-4" />
                </Button>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Insert Table"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('table') && 'bg-accent text-accent-foreground',
                    )"
                    @click="tableDialogOpen = true"
                >
                    <TableIcon class="size-4" />
                </Button>

                <template v-if="editor?.isActive('table')">
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Add Row Before"
                        class="h-8 w-8"
                        @click="editor?.chain().focus().addRowBefore().run()"
                    >
                        <Grid3x3 class="size-4 rotate-90" />
                    </Button>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Add Row After"
                        class="h-8 w-8"
                        @click="editor?.chain().focus().addRowAfter().run()"
                    >
                        <Grid3x3 class="size-4 -rotate-90" />
                    </Button>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Delete Row"
                        class="h-8 w-8"
                        @click="editor?.chain().focus().deleteRow().run()"
                    >
                        <Trash2 class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Add Column Before"
                        class="h-8 w-8"
                        @click="editor?.chain().focus().addColumnBefore().run()"
                    >
                        <Grid3x3 class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Add Column After"
                        class="h-8 w-8"
                        @click="editor?.chain().focus().addColumnAfter().run()"
                    >
                        <Grid3x3 class="size-4 scale-x-[-1]" />
                    </Button>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Delete Column"
                        class="h-8 w-8"
                        @click="editor?.chain().focus().deleteColumn().run()"
                    >
                        <Trash2 class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        title="Delete Table"
                        class="h-8 w-8 text-destructive"
                        @click="editor?.chain().focus().deleteTable().run()"
                    >
                        <Trash2 class="size-4" />
                    </Button>
                </template>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Insert Link"
                    :class="cn(
                        'h-8 w-8',
                        editor?.isActive('link') && 'bg-accent text-accent-foreground',
                    )"
                    @click="openLinkDialog"
                >
                    <LinkIcon class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Insert Image"
                    class="h-8 w-8"
                    @click="imageInputRef?.click()"
                >
                    <ImageIcon class="size-4" />
                </Button>
                <input
                    ref="imageInputRef"
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleImageUpload"
                />

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Insert YouTube Video"
                    class="h-8 w-8"
                    @click="youtubeDialogOpen = true"
                >
                    <Youtube class="size-4" />
                </Button>
            </div>

            <div class="mx-1 h-4 w-px bg-border" />

            <div class="flex items-center gap-1">
                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Undo"
                    class="h-8 w-8"
                    @click="editor?.chain().focus().undo().run()"
                >
                    <Undo class="size-4" />
                </Button>

                <Button
                    type="button"
                    variant="ghost"
                    size="icon-sm"
                    title="Redo"
                    class="h-8 w-8"
                    @click="editor?.chain().focus().redo().run()"
                >
                    <Redo class="size-4" />
                </Button>
            </div>
        </div>

        <EditorContent
            :editor="editor"
            class="resize-y overflow-auto px-3 py-2 text-sm text-foreground [&_.ProseMirror]:min-h-60 [&_.ProseMirror]:outline-none [&_.ProseMirror-focused]:outline-none [&_.ProseMirror-focused]:ring-0 [&_.ProseMirror_p.is-editor-empty:first-child::before]:pointer-events-none [&_.ProseMirror_p.is-editor-empty:first-child::before]:float-left [&_.ProseMirror_p.is-editor-empty:first-child::before]:text-muted-foreground [&_.ProseMirror_p.is-editor-empty:first-child::before]:content-[attr(data-placeholder)] [&_h1]:mb-2 [&_h1]:mt-5 [&_h1]:text-xl [&_h1]:font-bold [&_h2]:mb-2 [&_h2]:mt-4 [&_h2]:text-lg [&_h2]:font-semibold [&_h3]:mb-2 [&_h3]:mt-3 [&_h3]:text-base [&_h3]:font-semibold [&_h4]:mb-2 [&_h4]:mt-3 [&_h4]:text-sm [&_h4]:font-semibold [&_h5]:mb-2 [&_h5]:mt-2 [&_h5]:text-sm [&_h5]:font-medium [&_h6]:mb-2 [&_h6]:mt-2 [&_h6]:text-xs [&_h6]:font-medium [&_ol]:ml-4 [&_ol]:list-decimal [&_p]:mb-2 [&_p:last-child]:mb-0 [&_ul]:ml-4 [&_ul]:list-disc [&_blockquote]:border-l-2 [&_blockquote]:border-border [&_blockquote]:pl-4 [&_blockquote]:italic [&_hr]:my-4 [&_hr]:border-t [&_hr]:border-border [&_img]:max-w-full [&_img]:rounded-md [&_table]:w-full [&_table]:border-collapse [&_table]:text-sm [&_td]:border [&_td]:border-border [&_td]:px-2 [&_td]:py-1 [&_th]:border [&_th]:border-border [&_th]:bg-muted [&_th]:px-2 [&_th]:py-1 [&_th]:font-semibold"
        />
    </div>

    <Dialog v-model:open="linkDialogOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Insert Link</DialogTitle>
                <DialogDescription>
                    Enter the URL for the link.
                </DialogDescription>
            </DialogHeader>

            <div class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="link-url">URL</Label>
                    <Input
                        id="link-url"
                        v-model="linkUrl"
                        type="url"
                        placeholder="https://example.com"
                        @keydown.enter.prevent="setLink"
                    />
                </div>
            </div>

            <DialogFooter>
                <Button type="button" variant="outline" @click="linkDialogOpen = false">
                    Cancel
                </Button>
                <Button type="button" @click="setLink">Save Link</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <Dialog v-model:open="tableDialogOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Insert Table</DialogTitle>
                <DialogDescription>
                    Choose the number of rows and columns.
                </DialogDescription>
            </DialogHeader>

            <div class="grid grid-cols-2 gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="table-rows">Rows</Label>
                    <Input
                        id="table-rows"
                        v-model="tableRows"
                        type="number"
                        min="1"
                        max="20"
                    />
                </div>
                <div class="grid gap-2">
                    <Label for="table-cols">Columns</Label>
                    <Input
                        id="table-cols"
                        v-model="tableCols"
                        type="number"
                        min="1"
                        max="10"
                    />
                </div>
            </div>

            <DialogFooter>
                <Button type="button" variant="outline" @click="tableDialogOpen = false">
                    Cancel
                </Button>
                <Button type="button" @click="insertTable">Insert Table</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <Dialog v-model:open="youtubeDialogOpen">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Insert YouTube Video</DialogTitle>
                <DialogDescription>
                    Paste a YouTube video URL.
                </DialogDescription>
            </DialogHeader>

            <div class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="youtube-url">YouTube URL</Label>
                    <Input
                        id="youtube-url"
                        v-model="youtubeUrl"
                        type="url"
                        placeholder="https://youtube.com/watch?v=..."
                        @keydown.enter.prevent="insertYouTube"
                    />
                </div>
            </div>

            <DialogFooter>
                <Button type="button" variant="outline" @click="youtubeDialogOpen = false">
                    Cancel
                </Button>
                <Button type="button" @click="insertYouTube">Insert Video</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
