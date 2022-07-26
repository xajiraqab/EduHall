<?php require_once("../../session.php") ?>
<?php

if (!isset($_GET["u"])) {
  header("Location: ../books");
  die();
}

$book = Db::getBook($_GET["u"]);
if (!isset($book)) {
  header("Location: ../books");
  die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>EduHall - <?php echo $book["title"] ?></title>
  <meta name="description" content="<?php echo $book["description"] ?>" />
  <meta property="og:title" content="EduHall - <?php echo $book["title"] ?>" />
  <meta property="og:description" content="<?php echo $book["description"] ?>" />
  <meta property="og:url" content="https://www.eduhall.ge/" />
  <meta property="og:site_name" content="EduHall" />
  <meta property="og:image" content="../../images/<?php echo $book["image"] ?>" />
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="book.css">
  <link rel="icon" type="image/x-icon" href="../../images/favicon.png">
  <script src="../../js/pdf.js"></script>
</head>

<body>

  <?php include("../../partials/header.php"); ?>

  <?php if (_isAdmin()) : ?>
    <style>
      dialog {
        background: rgba(0, 0, 0, 0.3);
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        width: auto;
        height: auto;
        border: none;
        place-items: center;
        z-index: 3;
      }

      dialog h2 {
        margin-bottom: 1em;
      }

      .dialog_content {
        width: 600px;
        max-width: 100%;
        margin: auto;
        margin-top: 150px;
        background: white;
        padding: 3em;
        border-radius: 16px;
        box-shadow: 2px 4px 16px rgba(0, 0, 0, 0.1);
        animation: anShowUp ease 450ms;
      }

      .dialog_content form {
        position: relative;
        display: grid;
        gap: 1em;
      }

      .dialog_content label {
        flex: 1;
      }

      #btnSubmitAttachment {
        margin-top: 2em;
        position: relative;
        overflow: hidden;
      }

      #lblSubmitAttachment {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        line-height: 50px;
        color: #fafafa;
      }

      #file_container {
        display: flex;
        height: 50px;
        border-radius: 5px;
        background-color: #f1f1f1;
        color: #666;
        padding: 0.5em;
        font-style: italic;
        gap: .5em;
        cursor: pointer;
        position: relative;
        overflow: hidden;
      }

      #file_container span {
        line-height: 50px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      #loadingIndicator {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        background: linear-gradient(45deg, var(--clr_primaryLighter2), var(--clr_primary));
        transition: width ease 250ms;
      }

      #success {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: red;
        background: white;
        display: grid;
        place-items: center;
        display: none;
      }

      #success svg {
        fill: var(--clr_primary);
        animation: anShowUp ease 450ms;
      }

      @media only screen and (max-width: 600px) {

        #contact .grid_twocol {
          grid-template-columns: 1fr;
        }

        .dialog_content {
          margin-top: 50px;
          padding: 2em 1em;
          border-radius: 0;
        }

        .flex {
          display: grid;
        }
      }
    </style>

    <!-- მასალის დამატება -->
    <dialog>

      <div class="dialog_content">
        <form id="formAttachment">
          <h2><?php tr("ფაილის ატვირთვა", "Upload attachment") ?></h2>
          <div class="flex">
            <label class="txt">
              <div>
                <img width="18" src="../../images/united-kingdom.png" />
                <?php tr("დასახელება", "name") ?>
              </div>
              <input id="txtName" name="attachment" type="text" required />
            </label>

            <label class="txt">
              <div>
                <img width="18" src="../../images/georgia.png" />
                <?php tr("დასახელება ქართულად", "name georgian") ?>
              </div>
              <input id="txtNameGeo" name="attachment_geo" type="text" required />
            </label>
          </div>


          <label id="file_container">
            <input id="file" type="file" multiple hidden accept=".pdf, audio/mp3, video/mp4, video/webm, video/ogg" />
            <svg style="min-width: 24px" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#666">
              <path d="M0 0h24v24H0V0z" fill="none" />
              <path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z" />
            </svg>
            <span><?php tr("აირჩიეთ ფაილი..", "choose attachment..") ?></span>
          </label>

          <button id="btnSubmitAttachment">
            <div id="loadingIndicator"></div>
            <span id="lblSubmitAttachment"><?php tr("ატვირთვა", "upload") ?></span>
          </button>

          <div id="success">
            <svg xmlns="http://www.w3.org/2000/svg" height="124px" viewBox="0 0 24 24" width="124px" fill="#000000">
              <path d="M0 0h24v24H0V0z" fill="none" />
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z" />
            </svg>
          </div>
        </form>

        <script>
          let editingAttachmentId = -1
          const ui = {
            dialog: document.querySelector("dialog"),
            txtAttachment: document.querySelector("#file"),
            txtName: document.querySelector("#txtName"),
            txtNameGeo: document.querySelector("#txtNameGeo"),
            formAttachment: document.querySelector("#formAttachment"),
            lblAttachment: document.querySelector("#file_container span"),
            frameFileContainer: document.querySelector("#file_container"),
            btnSubmitAttachment: document.querySelector("#btnSubmitAttachment"),
            lblLoadingIndicator: document.querySelector("#loadingIndicator"),
            lblSubmitAttachment: document.querySelector("#lblSubmitAttachment"),
            lblSuccess: document.querySelector("#success"),
            lblTitle: document.querySelector("#formAttachment h2"),
          }

          // დიალოგის დახურვა
          const closeDialog = () => {

            // გათიშული სახელის შესაყვანის ჩართვა
            document.querySelectorAll("dialog .txt").forEach(txt => txt.classList.remove("disabled"))

            // required ის ჩართვა
            ui.txtName.setAttribute("required", true)
            ui.txtNameGeo.setAttribute("required", true)

            ui.btnSubmitAttachment.classList.remove("disabled")
            ui.lblLoadingIndicator.style.width = `0`
            ui.txtAttachment.value = ""
            ui.lblAttachment.innerText = "<?php tr("აირჩიეთ ფაილი..", "choose attachment..") ?>"
            ui.txtName.value = ""
            ui.txtNameGeo.value = ""
            ui.dialog.open = false
            ui.lblSuccess.style.display = "none"
          }

          // ფაილის არჩევისას სახელების ჩაწერა
          ui.txtAttachment.addEventListener("change", e => {
            const listFiles = ui.txtAttachment.files
            const size = listFiles.length

            // გათიშული სახელის შესაყვანის ჩართვა
            document.querySelectorAll("dialog .txt").forEach(txt => txt.classList.remove("disabled"))

            // required ის ჩართვა
            ui.txtName.setAttribute("required", true)
            ui.txtNameGeo.setAttribute("required", true)

            // თუ არაფელი აურჩევია
            if (!size) {
              ui.lblAttachment.innerText = "<?php tr("აირჩიეთ ფაილი..", "choose attachment..") ?>"
              return
            }

            // თუ ერთი ფაილი აირჩია
            if (size === 1) {
              const file = listFiles[0];
              ui.lblAttachment.innerText = file.name
              const filename = file.name.split('.')[0]
              ui.txtName.value = filename
              ui.txtNameGeo.value = filename
              return
            }

            // თუ რამდენიმე ფაილი აირჩია
            if (size > 1) {

              // ზემოთ ჩაწერილი სახელის გასუფთავება
              ui.txtName.value = ""
              ui.txtNameGeo.value = ""

              // required ის გამორთვა
              ui.txtName.removeAttribute("required")
              ui.txtNameGeo.removeAttribute("required")

              // ზემოთ სახელის ჩასაწერების გამორთვა
              document.querySelectorAll("dialog .txt").forEach(txt => {
                txt.classList.add("disabled")
              })

              // არჩეული ფაილების სახელების ჩაწერა
              let names = "";
              for (let i = 0; i < size; i++) {
                const file = listFiles[i];
                names += file.name.split('.').slice(0, -1).join(" ") + ", "
              }
              ui.lblAttachment.innerText = names.slice(0, -2);
            }
          })

          // დიალოგის გახსნა
          ui.dialog.addEventListener("click", e => {
            if (e.target !== e.currentTarget || ui.btnSubmitAttachment.classList.contains("disabled"))
              return
            closeDialog()
          })

          // წარმატებით ატვირთვის შეტყობინებაზე დაჭერსისას დახურვა დიალოგის
          ui.lblSuccess.addEventListener("click", () => {
            if (ui.lblSuccess.style.display === "grid")
              closeDialog()
          })

          // ატვირთვა
          ui.formAttachment.addEventListener("submit", e => {
            e.preventDefault()

            if (ui.btnSubmitAttachment.classList.contains("disabled") || ui.lblSuccess.style.display === "grid")
              return;

            let name = ui.txtName.value
            let name_geo = ui.txtNameGeo.value

            // თუ რედაქტირებაა
            if (editingAttachmentId !== -1) {

              ui.btnSubmitAttachment.classList.add("disabled")
              ui.formAttachment.style.pointerEvents = "none"

              JN.post("attachment_edit", {
                  id: editingAttachmentId,
                  name,
                  name_geo
                },
                () => {
                  ui.lblSuccess.style.display = "grid"
                  setTimeout(() => window.location.reload(), 1200);
                },
                error => {
                  alert(error)
                  ui.btnSubmitAttachment.classList.remove("disabled")
                  ui.formAttachment.style.pointerEvents = "all"
                  ui.lblLoadingIndicator.style.width = `0`
                }
              )
              return
            }

            const listFiles = ui.txtAttachment.files
            const sizeFiles = listFiles.length

            ui.btnSubmitAttachment.classList.add("disabled")
            ui.formAttachment.style.pointerEvents = "none"

            // ფაილების ატვირთვა
            let listUploadedFiles = []

            // ახალი ფაილის სახელების წამოღება
            JN.get("upload_attachment_get_new_filename", {
              count: sizeFiles
            }, async res => {
              listUploadedFiles = res

              for (let j = 0; j < sizeFiles; j++) {
                const file = listFiles[j];

                if (!file) {
                  alert("აირჩიეთ ფაილი!")
                  return
                }

                const format = file.name.split('.').pop()
                const filename = listUploadedFiles[j] + "." + format

                const chunkSize = 10000000 //10mb
                const url = "/api/upload_attachment.php"
                const size = file.size

                const totalWidth = ui.lblAttachment.clientWidth

                // თუ ბევრს ტვირთავს ერთდროულად, ფაილის დასახელება იქნება სახელი
                if (sizeFiles > 1) {
                  name = file.name.split('.').slice(0, -1).join(" ")
                  name_geo = name
                }

                // ატვირთულ ფაილებში გადატანა, მერე ერთად რომ შეინახოს ბაზაში მიყოლებით. :3
                listUploadedFiles[j] = {
                  url: filename,
                  format,
                  name,
                  name_geo,
                  book_id: <?php echo $book["id"] ?>
                }

                // ბაზაში შენახვა
                const index = j + 1

                // ლოადინგის დარესეთება
                ui.lblLoadingIndicator.style.transition = "none"
                ui.lblLoadingIndicator.style.width = `0`
                ui.lblLoadingIndicator.style.transition = undefined

                for (let i = 0; i <= size; i += Math.min(size - i, chunkSize)) {

                  const chunk = file.slice(i, i + chunkSize)
                  const fd = new FormData()
                  fd.set('data', chunk)
                  fd.set('filename', filename)

                  await fetch(url, {
                    method: 'post',
                    body: fd
                  }).then(res => res.text())

                  const percent = Math.floor(i * 1.0 / size * 100)
                  ui.lblLoadingIndicator.style.width = `${percent}%`
                  ui.lblSubmitAttachment.innerText = `<?php tr("ატვირთვა", "upload") ?> (${index}/${sizeFiles})`

                  if (i === size)
                    break
                }

                // ბაზაში შენახვა ანატვირთი ფაილების
                if (index === sizeFiles) {
                  JN.post("upload_attachment_save_to_db", {
                      listUploadedFiles
                    }, res => {

                      ui.lblSuccess.style.display = "grid"
                      setTimeout(() => window.location.reload(), 1200);
                    },
                    error => {
                      alert(error)
                      ui.btnSubmitAttachment.classList.remove("disabled")
                      ui.formAttachment.style.pointerEvents = "all"
                      ui.lblLoadingIndicator.style.width = `0`
                    })
                }
              }

            }, error => {
              alert(error)
              ui.btnSubmitAttachment.classList.remove("disabled")
              ui.formAttachment.style.pointerEvents = "all"
              ui.lblLoadingIndicator.style.width = `0`
            })


          })
        </script>
    </dialog>


    <script>
      const editAttachment = attachment => {
        editingAttachmentId = attachment.id
        ui.lblTitle.innerText = isGeorgian ? "რედაქტირება" : "update attachment"
        ui.lblSubmitAttachment.innerText = isGeorgian ? "განახლება" : "update"
        ui.frameFileContainer.style.display = "none"
        ui.txtName.value = attachment.name
        ui.txtNameGeo.value = attachment.name_geo
        ui.dialog.open = true
      }
      const deleteAttachment = (id, url) => {
        if (prompt("თუ გსურთ ფაილის წაშლა შეიყვანეთ: delete") !== "delete")
          return

        JN.post("attachment_delete", {
            id,
            url
          },
          () => window.location.reload(),
          error => alert(error)
        )
      }
    </script>
  <?php endif ?>


  <?php if (_isAdmin()) : ?>

    <!-- რედაქტირების და წაშლის ღილაკი -->
    <div class="buttons_flex floating_buttons">

      <!-- კოდების გენერაცია -->
      <div class="grid" style="margin-bottom: 2em">
        <label class="txt">
          <input id="txtGenerateCodesCount" placeholder="<?php tr('რაოდენობა', 'Count') ?>" name="generate_book_codes_count" type="number" required />
        </label>
        <button id="btnGenerateCodes" class=""><?php tr("კოდების გენერაცია", "generate codes") ?></button>
        <p class="statistics">

          <?php $stats = Db::getBookStatistics($book["id"]) ?>

          <strong><?php tr("სულ:", "total:") ?></strong><span><?php echo $stats["total"] ?></span>
          <strong><?php tr("ვადაგასული:", "expired:") ?></strong><span><?php echo $stats["expired"] ?></span>
          <strong><?php tr("არააქტიური:", "inactive:") ?></strong><span><?php echo $stats["total"] - $stats["active"] ?></span>
          <strong><?php tr("აქტიური:", "active:") ?></strong><span><?php echo $stats["active"] ?></span>
        </p>
        <script>
          const btnGenerateCodes = document.querySelector("#btnGenerateCodes")
          const txtGenerateCodesCount = document.querySelector("#txtGenerateCodesCount");
          btnGenerateCodes.addEventListener("click", () => {

            if (btnGenerateCodes.classList.contains("disabled"))
              return

            const count = parseInt(txtGenerateCodesCount.value)
            if (!count) {
              alert(isGeorgian ? "შეიყვანეთ რაოდენობა!" : "enter amount of book codes")
              return
            }

            if (prompt(`წიგნის ${count} კოდის გენერაციისთვის შეიყვანეთ სიტყვა: generate`) !== "generate")
              return

            btnGenerateCodes.classList.add("disabled")

            JN.post("generate_book_codes", {
              book_id: <?php echo $book["id"] ?>,
              count
            }, res => {

              let a = document.createElement('a');
              a.href = "/api/last_book_codes.zip";
              a.download = `book_codes_${new Date().toLocaleString()}.zip`;
              a.click();

              txtGenerateCodesCount.value = ""
              btnGenerateCodes.classList.remove("disabled")

            }, error => {
              alert(error)
              btnGenerateCodes.classList.remove("disabled")
            })
          })
        </script>
      </div>
      <button id="btnEdit" class="flat"><?php tr("რედაქტირება", "edit") ?></button>
      <button id="btnDelete" class="flat red"><?php tr("წაშლა", "delete") ?></button>

      <script>
        const id = <?php echo $book["id"] ?>;
        const image = "<?php echo $book["image"] ?>";
        document.querySelector("#btnEdit").addEventListener("click", () => {
          document.location.href = `/p/admin_book_edit/?u=${id}`
        })

        document.querySelector("#btnDelete").addEventListener("click", () => {
          if (prompt("თუ გსურთ წიგნის წაშლა შეიყვანეთ სიტყვა: delete?") !== "delete")
            return
          JN.post("book_delete", {
            id,
            image
          }, () => window.location.href = "/", error => alert(error))
        })
      </script>
    </div>

    <!-- ადმინის მენიუს გასახსნელი -->
    <style>
      #btnToggleAdminMenu {
        position: fixed;
        right: 10px;
        bottom: 20px;
        z-index: 2;
      }

      #btnAdminMenuCloser {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 1;
      }

      #btnToggleAdminMenu svg {
        animation: anShowUpBiggerScale ease 450ms;
      }
    </style>

    <!-- უკანა ფონზე დაჭერისას მენიუს დახურვა -->
    <div id="btnAdminMenuCloser" class="hidden"></div>

    <!-- მენიუს გახსნის ღილაკი -->
    <button id="btnToggleAdminMenu" class="action white larger">

      <!-- მენიუს იკონი -->
      <svg id="iconMenu" title="iconMenu" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
        <path d="M0 0h24v24H0V0z" fill="none" />
        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
      </svg>

      <!-- მენიუს დახურვის იკონი -->
      <svg id="iconClose" title="iconClose" class="hidden" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
        <path d="M0 0h24v24H0V0z" fill="none" />
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
      </svg>
    </button>

    <script>
      (() => {

        const ui = {
          adminMenu: document.querySelector(".floating_buttons"),
          iconMenu: document.querySelector("#iconMenu"),
          iconClose: document.querySelector("#iconClose"),
          btnAdminMenuCloser: document.querySelector("#btnAdminMenuCloser"),
          btnToggleAdminMenu: document.querySelector("#btnToggleAdminMenu")
        }

        ui.btnToggleAdminMenu.addEventListener("click", () => {
          ui.iconMenu.classList.toggle("hidden")
          ui.iconClose.classList.toggle("hidden")
          ui.btnAdminMenuCloser.classList.toggle("hidden")
          ui.adminMenu.classList.toggle("open")
        })

        ui.btnAdminMenuCloser.addEventListener("click", () => {
          ui.btnToggleAdminMenu.click()
        })

      })()
    </script>
  <?php endif ?>


  <main>
    <div class="book_info">
      <div>
        <span class="img_container"><img width="100%" src="../../images/<?php echo $book["image"] ?>" alt="book cover" /></span>
      </div>

      <!-- სათაური -->
      <div class="col_right">
        <div>
          <h1><?php echo $book["title"] ?></h1>
          <p><span><?php tr("ავტორები", "Authors") ?>:</span> <?php echo $book["authors"] ?></p>
          <p><span><?php tr("გამოშვების წელი", "Year") ?>:</span> <?php echo $book["year"] ?></p>
        </div>

        <!-- აღწერა -->
        <?php if ($book["description"]) : ?>
          <div class="grid">
            <h2><?php tr("აღწერა", "description") ?></h2>
            <?php echo $book["description"] ?>
          </div>
        <?php endif; ?>


      </div>
    </div>


    <!-- დამხმარე მასალა -->
    <?php if ($_user && $_user["is_active"] && ($_user["is_admin"] || Db::isMyBook($_user["id"], $book["id"]))) : ?>
      <div class="grid">
        <?php $listAttachments = DB::getListAttachments($book["id"]) ?>
        <?php if ($_user["is_admin"] || count($listAttachments)) : ?>
          <div class="flex">
            <h2 style="align-self: end"><?php tr("დამხმარე მასალა", "attachments") ?></h2>

            <?php if ($_user["is_admin"]) : ?>

              <!-- დამხმარე მასალის დამატება -->
              <button id="btnAttachmentAdd" style="margin-left: auto; margin-right: 10px;" class="flat with_icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="var(--clr_primary)">
                  <path d="M0 0h24v24H0V0z" fill="none" />
                  <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                </svg>
                <?php tr("მასალის ატვირთვა", "upload attachment") ?>
              </button>
              <script>
                document.querySelector("#btnAttachmentAdd").addEventListener("click", () => {
                  editingAttachmentId = -1
                  ui.lblTitle.innerText = isGeorgian ? "ფაილის ატვირთვა" : "Upload Attachment"
                  ui.lblSubmitAttachment.innerText = isGeorgian ? "ატვირთვა" : "upload"
                  ui.frameFileContainer.style.display = "flex"
                  ui.txtName.value = ""
                  ui.txtNameGeo.value = ""
                  ui.dialog.open = true
                })
              </script>
            <?php endif ?>
          </div>

          <!-- დამხმარე მასალის ჩამონათვალი -->
          <div class="attachments">
            <?php foreach ($listAttachments as $attachment) : ?>
              <?php if ($attachment["format"] === "pdf") : ?>

                <!-- pdf -->
                <div class="pdf" onclick='if (event.target === event.currentTarget) openPdf("<?php echo $attachment["url"] ?>")'>
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm12 6V9c0-.55-.45-1-1-1h-2v5h2c.55 0 1-.45 1-1zm-2-3h1v3h-1V9zm4 2h1v-1h-1V9h1V8h-2v5h1zm-8 0h1c.55 0 1-.45 1-1V9c0-.55-.45-1-1-1H9v5h1v-2zm0-2h1v1h-1V9z" />
                  </svg>
                  <?php echo $attachment[$_isGeorgian ? "name_geo" : "name"] ?>
                  <?php if ($_user["is_admin"]) : ?>


                    <!-- რედაქტირება / წაშლა  -->
                    <div class="attachment_menu">
                      <button onClick='editAttachment(<?php echo json_encode($attachment) ?>)' class="action orange"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="white">
                          <path d="M0 0h24v24H0z" fill="none" />
                          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                        </svg>
                      </button>
                      <button onClick="deleteAttachment(<?php echo $attachment['id'] ?>, '<?php echo $attachment['url'] ?>')" class="action red"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="currentColor">
                          <path d="M0 0h24v24H0z" fill="none" />
                          <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                        </svg>
                      </button>
                    </div>

                  <?php endif ?>

                </div>
              <?php
                continue;
              endif;
              ?>
              <details>
                <summary>

                  <!-- მუსიკის იკონი -->
                  <?php if ($attachment["format"] === "mp3") : ?>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor">
                      <path d="M0 0h24v24H0V0z" fill="none" />
                      <path d="M19 14v3c0 .55-.45 1-1 1h-1v-4h2M7 14v4H6c-.55 0-1-.45-1-1v-3h2m5-13c-4.97 0-9 4.03-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7c0-4.97-4.03-9-9-9z" />
                    </svg>

                    <!-- ვიდეოს იკონი -->
                  <?php else : ?>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor">
                      <path d="M0 0h24v24H0V0z" fill="none" />
                      <path d="M15 8v8H5V8h10m1-2H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4V7c0-.55-.45-1-1-1z" />
                    </svg>
                  <?php endif ?>

                  <?php echo $attachment[$_isGeorgian ? "name_geo" : "name"] ?>

                  <?php if ($_user["is_admin"]) : ?>

                    <!-- რედაქტირებისა და წაშლის ღილაკები -->
                    <div class="attachment_menu">
                      <button onClick='editAttachment(<?php echo json_encode($attachment) ?>)' class="action orange" style="margin-left: auto;"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="white">
                          <path d="M0 0h24v24H0z" fill="none" />
                          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                        </svg>
                      </button>
                      <button onClick="deleteAttachment(<?php echo $attachment['id'] ?>, '<?php echo $attachment['url'] ?>')" class="action red"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="currentColor">
                          <path d="M0 0h24v24H0z" fill="none" />
                          <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                        </svg>
                      </button>
                    </div>
                  <?php endif ?>


                </summary>
                <p>

                  <!-- მუსიკა -->
                  <?php if ($attachment["format"] === "mp3") : ?>
                    <audio controls controlsList="nodownload" oncontextmenu="return false">
                      <source src="../../data/<?php echo $attachment["url"] ?>">
                    </audio>

                    <!-- ვიდეო -->
                  <?php else : ?>
                    <video controls controlsList="nodownload" oncontextmenu="return false">
                      <source src="../../data/<?php echo $attachment["url"] ?>" type="video/mp4">
                    </video>
                  <?php endif ?>
                </p>
              </details>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      </div>
    <?php endif ?>
    <script src="book.js"></script>
  </main>

  <?php include("../../partials/footer.php"); ?>

</body>

</html>