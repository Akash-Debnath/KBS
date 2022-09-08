<template>

  <div>
    <textarea  :id="idFromParent" class="form-control summernote" cols="30" rows="6" :value="dataFromParent"> </textarea>
  </div>

</template>

<script>

import $ from 'jquery'
import '../../../node_modules/summernote/dist/summernote-lite.min.js'
import '../../../node_modules/summernote/dist/summernote-lite.min.css'
import axios from 'axios'

export default {
  name: "summernote-edit",
  props: ['idFromParent', 'dataFromParent'],
  data() {
    return {
      obj: ""
    };
  },
  mounted() {

    this.textId = this.dataFromParent;
    console.log(this.textId);

    let fontsArray = ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Roboto'];
    const vm = this;
    const options = {
      lang: 'en-US',
      height: 150,
      dialogsInBody: true,
      fontNames: fontsArray,
      fontNamesIgnoreCheck: fontsArray,
      fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '18', '20', '24', '30', '36', '48' , '64', '82', '150'],
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
        ['fontname', ['fontname']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['color', ['color']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview']],
        ['height', ['height']],
        ['fontsize', ['fontsize']]
      ]
    };

    options.callbacks = {

      onChange: (contents)=> {

        vm.$emit("textarea", contents);

      },

      onImageUpload: function (files) {


        let data = new FormData();
        data.append("file", files[0]);
        data.append("attachment_save_path", 'article/images');

        $.ajax({

          data: data,
          type: "POST",
          url: axios.defaults.baseURL + 'save-file',
          headers: {  'Authorization': 'Bearer '+localStorage.getItem('authToken') },
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(data) {
            console.log(data);
            if(data.status_code == 200) {
              let image = $('<img>').attr('src', data.file_path).attr('style', 'height: auto;').attr('alt', 'image');

              console.log(vm.idFromParent);

              if(vm.idFromParent == 'bn_Body_edit') {

                $('#bn_Body_edit.summernote').summernote("insertNode", image[0]);

              } else {

                $('#en_Body_edit.summernote').summernote("insertNode", image[0]);

              }

            }
          }
        });

      },

      onMediaDelete : function(target) {

        let img_src = target[0].src;

        $.ajax({

          data: {img_src: img_src},
          type: "POST",
          url: axios.defaults.baseURL + 'delete-file',
          headers: {  'Authorization': 'Bearer '+localStorage.getItem('authToken') },
          cache: false,
          dataType: 'json',
          success: function(data) {
            // console.log(data);
            if(data.status_code != 200){

              alert('Not Delete From Server.');

            }
          }

        });
      }


    };

    $('textarea.summernote').summernote(options);

  },
}
</script>

<style scoped>

</style>
