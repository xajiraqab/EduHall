
const JN = {
  https: false, // ჯერ არ გამოიყენება
  json: true,

  test: "/eduhall.git",

  get: (url, params, callback, error) => {
      let xhr = new XMLHttpRequest();
      xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
              if (xhr.status === 200) {
                  // console.log(xhr.responseText)
                  // console.log(JSON.parse(xhr.responseText))
                  callback(JN.json ? JSON.parse(xhr.responseText) : xhr.responseText)

              }
              else {
                  error(JN.json ? JSON.parse(xhr.statusText) : xhr.statusText)
              }
          }
      }

      xhr.open("GET", `${JN.test}/api/${url}.php?data=${JSON.stringify(params)}`, true); // true for asynchronous 
      xhr.send(null);
  },


  post: (url, params, callback, error) => {
      let xhr = new XMLHttpRequest();
      xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
              if (xhr.status === 200) {
                  // console.log(xhr.responseText)
                  let response = JN.json && xhr.responseText !== "" ? JSON.parse(xhr.responseText) : xhr.responseText
                  if (response.error) {
                      error(response.error)
                  }
                  else {
                      callback(response)
                  }

              }
              else {
                  error(JN.json ? JSON.parse(xhr.responseText) : xhr.responseText)
              }
          }
      }

      xhr.open("POST", `${JN.test}/api/${url}.php`, true); // true for asynchronous 
      xhr.send(JSON.stringify(params));
  },


  //პარამეტრების ობიექტიდან მისამართის აწყობა.  < #??? აქ არ გამოიყენება, რადგან json-ით იგზავნება მარტო data პარამეტრი. >
  paramsOb_to_url: (params) => {
      let url_params = ''
      for (const [key, value] of Object.entries(params)) {
          url_params += `${key}=${value}&`
      }
      return url_params.slice(0, -1)
  },

}