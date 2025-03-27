    function showForm(formType) {
        document.getElementById("main-section").style.display = "none";
        document.getElementById("login-form").style.display = "none";
        document.getElementById("register-form").style.display = "none";

        if (formType === "login") {
            document.getElementById("login-form").style.display = "block";
        } else if (formType === "register") {
            document.getElementById("register-form").style.display = "block";
        } else {
            document.getElementById("main-section").style.display = "block";
        }
    }

    function toggleDoctorFields() {
        document.getElementById("doctorFields").style.display = 
            (document.getElementById("role").value == "doctor") ? "block" : "none";
    }