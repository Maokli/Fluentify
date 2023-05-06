export function showLoader() {
  const body = document.body;
  const overlay = document.createElement("div");
  overlay.className = "loader-overlay";
  const loader = document.createElement("div");
  loader.className = "loader";

  overlay.appendChild(loader);

  body.appendChild(overlay);
}

export function hideLoader() {
  const loader = document.querySelector(".loader-overlay");

  loader.remove();
}