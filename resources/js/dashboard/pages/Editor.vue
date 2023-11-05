<template>
  <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    const editorData = ref("<p>Content of the editor.</p>");

    return {
      editor: ClassicEditor,
      editorData,
      editorConfig: {
        // The configuration of the editor.
        table: {
          contentToolbar: ["tableColumn", "tableRow", "mergeTableCells"],
          tableRow: {
            defaultNumber: 10, // Thay đổi số hàng mặc định thành 10.
          },
        },
      },
    };
  },
});
</script>
