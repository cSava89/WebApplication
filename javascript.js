

function O(i) { return typeof i == 'object' ? i : document.getElementById(i) }
function S(i) { return O(i).style                                            }
function C(i) { return document.getElementsByClassName(i)                    }
                //input,dove la risposta verrà inserita,il checker ed il valore di chiave del campo post
function checkUser(user,idwhere,checker,key)//funzione per verificare la politica dei campi inseriti nella sezione di registrazione tramite chiamate ajax.Ho creato quattro parametri distintivi per poter differenziare le varie chiamate
    {
        
      if (user.value == '')
      {
        O(idwhere).innerHTML = ''
        return
      
      }

      params  = key + user.value
      request = new ajaxRequest()
      request.open("POST", checker, true)
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
      request.setRequestHeader("Content-length", params.length)
      request.setRequestHeader("Connection", "close")

      request.onreadystatechange = function()
      {
        if (this.readyState == 4)
          if (this.status == 200)
            if (this.responseText != null)
              O(idwhere).innerHTML = this.responseText
      }
      request.send(params)
    }

    function ajaxRequest()
    {
      try { var request = new XMLHttpRequest() }
      catch(e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP") }
        catch(e2) {
          try { request = new ActiveXObject("Microsoft.XMLHTTP") }
          catch(e3) {
            request = false
      } } }
      return request
    }
