import axiosInstance from '../helpers/axiosInstanceClient.js';
//creating a card for each language
function createLanguageCard(language) {
  return `
    <div class="col-md-3 mb-4">
      <div class="card h-100" style="width: 13rem; height: 10rem;">
        <div class="d-flex justify-content-center">
          <img src="${language.photoUrl}" class="card-img-top rounded-start flags text-center" alt="${language.name} flag">
        </div>
        <div class="card-body d-flex flex-column justify-content-between">
          <h5 class="card-title text-center mt-3">${language.name}</h5>
          <div class="d-flex justify-content-between align-items-center">
            <p>${language.description}</p>
          </div>
          <button class="btn btn-primary"><i class="bi bi-plus"></i>Join them !</button>
        </div>
      </div>
    </div>
  `;
}

function setAsJoined(button)
{
  button.disabled = true;
  button.classList = "btn btn-success";
  button.textContent = "Joined";
}

async function AddToFavourite(languageId, button) {
  const payload = { id: languageId };

  try {
    // we call the api
    await axiosInstance.post("languages/Favorite", payload);
    // if no exceptions, we set it as joined
    setAsJoined(button);

  } catch (error) {
    alert("something went wrong");
  }
}


async function renderPage() {
const allLanguagesResponse = await axiosInstance.get('/languages');
  //getting languages from the database
  const languages=allLanguagesResponse.data;
  const cardContainer= document.querySelector('#cardContainer');

  // gettung favourite languages
  const favouriteLanguagesResponse = await axiosInstance.get('/languages/Favorite');
  const favouriteLanguagesIds = favouriteLanguagesResponse.data.map(language => language.languageId);
  //injecting each card int the card container
  languages.forEach(language=> {
    const card = createLanguageCard(language);
    const cardElement = document.createRange().createContextualFragment(card).firstElementChild;
    const joinBtn = cardElement.querySelector("button");
    // langyuage is already favourited
    if(favouriteLanguagesIds.includes(language.id))
    {
      setAsJoined(joinBtn);
    }
    else {
      joinBtn.addEventListener("click", () => {
        AddToFavourite(language.id, joinBtn);
      })
    }
    cardContainer.appendChild(cardElement);
  });
}

renderPage();
