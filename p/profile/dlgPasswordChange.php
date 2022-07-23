<dialog id="dlgPasswordChange">

  <style>
    #btnSubmitPassword {
      margin-top: 2em;
    }

    #lblSuccessPasswordText {
      margin-top: -5em;
    }

    @media only screen and (max-width: 600px) {
      main.profile {
        grid-template-columns: 1fr;
        padding: 2em 1em;
        border-radius: 0;
        margin: 50px auto;
      }

      #contact .grid_twocol {
        grid-template-columns: 1fr;
      }
    }
  </style>


  <div class="dialog_content">
    <form id="formPassword">

      <!-- სათაური -->
      <h2><?php tr("პაროლის შეცვლა", "change password") ?></h2>

      <!-- ძველი -->
      <div class="txtContainer">
        <label class="txt">
          <?php tr("ძველი პაროლი", "old password") ?>
          <input id="txtOldPassword" type="password" required />
        </label>
        <span id="lblErrorOldPassword">*<?php tr("ძველი პაროლი არასწორია", "old password is incorrect") ?></span>
      </div>

      <!-- ახალი პაროლი -->
      <div class="txtContainer">
        <label class="txt">
          <?php tr("ახალი პაროლი", "new password") ?>
          <input id="txtNewPassword" type="password" required />
        </label>
      </div>

      <!-- გააქტიურება -->
      <button id="btnSubmitPassword">
        <div id="loadingIndicatorPassword"></div><?php tr("შეცვლა", "submit") ?>
      </button>

      <!-- წარმატების მაჩვენებელი -->
      <div id="lblSuccessPassword">
        <svg xmlns="http://www.w3.org/2000/svg" height="124px" viewBox="0 0 24 24" width="124px" fill="#000000">
          <path d="M0 0h24v24H0V0z" fill="none" />
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z" />
        </svg>
        <span id="lblSuccessPasswordText"><?php tr("პაროლი წარმატებით შეიცვალა", "password updated successfully") ?></span>
      </div>

    </form>


    <script>
      (() => {

        const ui = {
          dialog: document.querySelector("#dlgPasswordChange"),
          formPassword: document.querySelector("#formPassword"),
          txtOldPassword: document.querySelector("#txtOldPassword"),
          txtNewPassword: document.querySelector("#txtNewPassword"),
          lblSuccess: document.querySelector("#lblSuccessPassword"),
          lblErrorOldPassword: document.querySelector("#lblErrorOldPassword"),
          btnSubmit: document.querySelector("#btnSubmitPassword"),
        }

        // დიალოგის დახურვა
        const closeDialog = () => {
          ui.btnSubmit.classList.remove("disabled")
          ui.lblSuccess.style.display = "none"
          ui.lblErrorOldPassword.style.display = "none"
          ui.txtOldPassword.value = ""
          ui.txtNewPassword.value = ""
          ui.dialog.open = false
        }

        // დიალოგის დახურვა
        ui.dialog.addEventListener("click", e => {
          if (e.target !== e.currentTarget || ui.btnSubmit.classList.contains("disabled"))
            return
          closeDialog()
        })

        // პაროლის შეცვლა
        ui.formPassword.addEventListener("submit", e => {
          e.preventDefault()
          if (ui.btnSubmit.classList.contains("disabled"))
            return

          ui.btnSubmit.classList.add("disabled")

          JN.post("change_password", {
              oldPassword: ui.txtOldPassword.value,
              newPassword: ui.txtNewPassword.value
            },
            res => {

              // ძველი პაროლი არასწორია
              if (res.status === -1) {
                lblErrorOldPassword.style.display = "block"
                ui.btnSubmit.classList.remove("disabled")
              }

              // წარმატებით შეიცვალა
              else {
                ui.lblSuccess.style.display = "grid"
                setTimeout(() => {
                  closeDialog()
                }, 2000);
              }
            },
            error => {
              alert(error)
              ui.btnSubmit.classList.remove("disabled")
            }
          )

        })


      })()
    </script>

</dialog>