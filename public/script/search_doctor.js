// تفعيل خانات البحث حسب الاختيار
document.querySelectorAll('input[name="searchOption"]').forEach((option) => {
  option.addEventListener("change", function () {
    document.getElementById("searchName").disabled = this.value !== "name";
    document.getElementById("searchSpecialty").disabled =
      this.value !== "specialty";
  });
});

// فلترة الأطباء حسب الاسم أو التخصص
function searchDoctors() {
  let searchOption = document.querySelector(
    'input[name="searchOption"]:checked'
  );
  if (!searchOption) return;

  let doctors = document.querySelectorAll(".doctor-item");
  let keyword = "";

  if (searchOption.value === "name") {
    keyword = document.getElementById("searchName").value.toLowerCase();
  } else if (searchOption.value === "specialty") {
    keyword = document.getElementById("searchSpecialty").value.toLowerCase();
  }

  doctors.forEach((doctor) => {
    let text = doctor.textContent.toLowerCase();
    doctor.style.display = text.includes(keyword) ? "block" : "none";
  });
}

// منع الإرسال الافتراضي للنموذج
document.getElementById("searchForm").addEventListener("submit", function (e) {
  e.preventDefault();
  searchDoctors();
});
