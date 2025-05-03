document.getElementById("file-upload").addEventListener("change", function () {
  var fileName =
    this.files.length > 0 ? this.files[0].name : "لم يتم اختيار أي ملف";
  document.getElementById("file-name").textContent = fileName;
});
