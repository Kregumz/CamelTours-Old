var cache = window.applicationCache;
var currentFile, totalFiles, percent, progress;
var bar = document.getElementById("loadbar");
var main = document.getElementById("main");
var sub = document.getElementById("sub");
var note = document.getElementById("note");
function handleCacheEvent(event) {
  if (event.type == "checking") {
    sub.innerHTML = "Checking for updates...";
  }
  else if (event.type == "downloading") {
    currentFile = 0;
    sub.innerHTML = "Update found. Fetching resources...";
  }
  else if (event.type == "progress") {
    percent = currentFile / totalFiles * 100;
    bar.setAttribute("style", "width: " + percent + "%");
    progress = "Files remaining: " + (totalFiles - currentFile++);
    sub.innerHTML = progress;
  }
  else if (event.type == "updateready" || event.type == "cached") {
    if (localStorage.visited == "true" && localStorage.site != null)
      setTimeout(function(){windows.location = localStorage.site;}, 3000);
    localStorage.visited = "true";
    bar.setAttribute("style", "width: 100%");
    main.innerHTML = "Congratulations! You downloaded the tour!";
    sub.innerHTML = "You may now proceed to the next point on the tour and scan the QR code.";
    note.innerHTML = "A wireless connection is no longer needed beyond this point.";
  }
  else if (event.type == "noupdate") {
    bar.setAttribute("style", "width: 100%");
    main.innerHTML = "All tour content has already been downloaded!";
    sub.innerHTML = "You may now proceed to the next point on the tour and scan the QR code.";
    note.innerHTML = "A wireless connection is no longer needed beyond this point."
  }
}
// Set regular cache events.
var cacheEvents = ["checking", "downloading", "progress", "cached", "noupdate", "updateready", "obsolete"];
for (var i = 0; i < cacheEvents.length; i++)
  cache.addEventListener(cacheEvents[i], handleCacheEvent, false);
// Set cache error event.
function handleCacheError(event) {
  if (progress) {
    main.innerHTML = "Uh oh! We've hit an error. Please try refreshing the page.";
    sub.innerHTML = "If the problem persists, upgrade your software or use a different browser.";
  }
  else {
    main.innerHTML = "Uh oh! CamelTours can't download this tour to your device.";
    sub.innerHTML = "Make sure you're not in private browsing mode or blocking cookies.";
  }
  document.getElementById("loadbox").style.display = "none";
  document.getElementById("help").style.display = "block";
}
cache.addEventListener("error", handleCacheError, false);