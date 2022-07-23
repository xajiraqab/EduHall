const openPdf = (url) => {

  const pdfDiv = document.createElement("div");
  pdfDiv.id = 'pdf'

  const pdfCoverDiv = document.createElement("div");
  pdfCoverDiv.onclick = () => {
    pdfDiv.remove()
    pdfCoverDiv.remove()
  }
  pdfCoverDiv.id = 'pdfCover'

  const iframe = document.createElement("iframe");
  iframe.width = '100%'
  iframe.height = '100%'
  iframe.src = `/eduhall.git/ViewerJS/?zoom=page-width#../data/${url}`
  pdfDiv.appendChild(iframe)

  document.body.appendChild(pdfCoverDiv)
  document.body.appendChild(pdfDiv)


}