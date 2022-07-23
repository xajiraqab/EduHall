(() => {
  const ui = {
    form: document.querySelector("#form_contact"),
    txtEmail: document.querySelector("#txtEmail"),
    txtMessage: document.querySelector("#txtMessage"),
    btnSend: document.querySelector("#btnSend"),
    lblSuccess: document.querySelector("#lblSuccess"),
  }

  ui.form.addEventListener("submit", e => {
    e.preventDefault()
    if (ui.btnSend.classList.contains("disabled"))
      return

    ui.btnSend.classList.add("disabled")

    JN.post("send_message", {
      email: ui.txtEmail.value,
      message: ui.txtMessage.value
    },
      () => {
        ui.btnSend.style.visibility = "hidden"
        ui.lblSuccess.style.display = "grid"

        setTimeout(() => {
          ui.txtMessage.value = ""
          ui.btnSend.classList.remove("disabled")
          ui.btnSend.style.visibility = "visible"
          ui.lblSuccess.style.display = "none"
        }, 5000);
      },
      error => {
        alert(error)
        ui.btnSend.classList.remove("disabled")
      })
  })
})()