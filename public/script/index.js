function showForm(formType) {
  document.getElementById("main-section").style.display = "none";
  document.getElementById("aboutt").style.display = "none";
  document.getElementById("services").style.display = "none";
  document.getElementById("contact").style.display = "none";
  document.getElementById("login-form").style.display = "none";
  document.getElementById("register-form").style.display = "none";

  if (formType === "login") {
    document.getElementById("login-form").style.display = "flex";
  } else if (formType === "register") {
    document.getElementById("register-form").style.display = "flex";
  } else {
    document.getElementById("main-section").style.display = "none";
    document.getElementById("aboutt").style.display = "block";
    document.getElementById("services").style.display = "block";
    document.getElementById("contact").style.display = "block";
  }
}

function toggleDoctorFields() {
  const role = document.getElementById("role").value;
  const doctorFields = document.getElementById("doctorFields");

  if (role === "doctor") {
    doctorFields.style.display = "block";
    doctorFields
      .querySelectorAll("select, textarea")
      .forEach((el) => (el.required = true));
  } else {
    doctorFields.style.display = "none";
    doctorFields
      .querySelectorAll("select, textarea")
      .forEach((el) => (el.required = false));
  }
}

window.onload = function () {
  // إظهار حقول الطبيب إذا كان الدور المختار هو طبيب
  if (document.getElementById("role").value === "doctor") {
    document.getElementById("doctorFields").style.display = "block";
  }

  // إخفاء رسائل التنبيه بعد 5 ثواني
  setTimeout(() => {
    const successMsg = document.getElementById("success-message");
    const errorMsg = document.getElementById("error-message");
    if (successMsg) successMsg.style.display = "none";
    if (errorMsg) errorMsg.style.display = "none";
  }, 5000);

  // منع إعادة إرسال النموذج عند تحديث الصفحة
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
};
// تأخير الأنيميشن بعد تحميل الصفحة
window.onload = function () {
  setTimeout(function () {
    document.querySelector(".animated").classList.add("show");
  }, 2000); // تأخير 2 ثانية
};
function toggleDoctorFields() {
  var role = document.getElementById("role").value;
  var doctorFields = document.getElementById("doctorFields");
  doctorFields.style.display = role === "doctor" ? "block" : "none";
}

// لإعادة إظهار الحقول بعد إعادة تحميل الصفحة إذا تم اختيار "doctor"
window.onload = function () {
  toggleDoctorFields();
};
// burger menu
const burger = document.querySelector(".burger");
if (burger) {
  burger.addEventListener("click", function () {
    document.querySelector("nav.nav-links").classList.toggle("active");
  });
}
