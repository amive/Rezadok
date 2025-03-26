function showForm(formType) {
  document.getElementById("main-section").style.display = "none";
  document.getElementById("login-form").style.display =
    formType === "login" ? "block" : "none";
  document.getElementById("register-form").style.display =
    formType === "register" ? "block" : "none";
}

function toggleDoctorFields() {
  document.getElementById("doctorFields").style.display =
    document.getElementById("role").value == "doctor" ? "block" : "none";
}
