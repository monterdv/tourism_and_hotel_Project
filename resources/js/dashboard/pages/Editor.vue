<template>
  <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    // Define a reactive variable for the editor content
    const editorData = ref(
      `<table>${Array.from(
        { length: 10 },
        (_, index) => `<tr><td>Row ${index + 1}</td></tr>`
      ).join("")}</table>`
    );

    // Return data and configurations to be used in the component
    return {
      editor: ClassicEditor, // CKEditor instance
      editorData, // Reactive variable for the editor content
      editorConfig: {
        // The configuration of the editor.
        table: {
          contentToolbar: ["tableColumn", "tableRow", "mergeTableCells"], // Table-related toolbar options
          tableRow: {
            defaultNumber: 10, // Default number of rows in a table
          },
        },
      },
    };
  },
});
</script>
