function shortenUrl() {
  const urlInput = document.getElementById('url-input');
  const responseMessage = document.getElementById('response-message');

  let error = false;

  fetch(`/url-shortener.php?url=${urlInput.value}`)
    .then(res => {
      error = !res.ok;
      return res.text();
    })
    .then(res => {
      if (error) {
        responseMessage.classList.add('error');
        responseMessage.innerText = `Error: ${res}`;
      } else {
        responseMessage.classList.remove('error');
        responseMessage.innerText = res;
      }
    });
}
