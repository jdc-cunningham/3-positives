// this code runs in the console
// this is looping over a DOM structure like this:
// ...
// <div>
//   <span></span>
//   <span></span>
// <div>
// ...

let jsonObj = {};

Array.from(document.getElementsByTagName('div')).forEach(div => {
  let spanChildren = div.getElementsByTagName('span');
  jsonObj[spanChildren[0].innerText] = spanChildren[1].innerText;
});

console.log(jsonObj);