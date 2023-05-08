import axiosInstance from '../helpers/axiosInstanceClient.js';

const DIFFICULITY_LEVELS = ["Beginner", "Intermediate", "Advanced"]
const USER_LANGUAGES = [];
let messages = null;


async function getUserLanguages() {
  const response = await axiosInstance.get('/languages/Favorite');

  //getting languages from the database
  const languages=response.data;

  //injecting each card int the card container
  languages.forEach(language=> {
    USER_LANGUAGES.push(language.languageName);
  });
}


function getCard(text) {
  /*
    <div class="col">
      <div class="card hoverable px-3 py-5">
        <h2 class="text-center">Beginner</h2>
      </div>
    </div> 
  */

  const col = document.createElement('div');
  col.className = "col";
  const card = document.createElement("div");
  card.className = "card hoverable px-3 py-5";
  const title = document.createElement("h2");
  title.className = "text-center";
  title.innerText = text;

  card.appendChild(title);
  col.appendChild(card);


  return col;
}

async function renderLanguageSelection() {
  await getUserLanguages();
  const main = document.querySelector("main");
  const container = document.createElement('div');
  container.className = "row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4";
  USER_LANGUAGES.forEach(language => {
    const languageCard = getCard(language);

    languageCard.addEventListener("click", () => {
      window.language = language;
      container.remove();
      renderChatSection();
    });

    container.appendChild(languageCard);
  });

  main.appendChild(container);
}

function renderDifficulitySelection() {
  const main = document.querySelector("main");
  const container = document.createElement('div');
  container.className = "row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4";
  DIFFICULITY_LEVELS.forEach(difficulity => {
    const difficulityCard = getCard(difficulity);

    difficulityCard.addEventListener("click", () => {
      window.difficulity = difficulity;
      container.remove();
      renderLanguageSelection();
    });

    container.appendChild(difficulityCard);
  });

  main.appendChild(container);
}

function renderChatSection(){
  const chatHTML = `
    <div class="card card-bordered">
          <div class="card-header">
            <h4 class="card-title"><strong>Chat</strong></h4>
          </div>


          <div class="ps-container ps-theme-default ps-active-y" id="chat-content" style="overflow-y: scroll !important; height:75vh !important;">
            

          <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;"></div></div></div>

          <form class="publisher bt-1 border-light">
            <input class="publisher-input" type="text" placeholder="Write something">
            <button class="publisher-btn text-info" type="submit" data-abc="true"><i class="fa fa-paper-plane"></i></a>
          </form>

    </div>`

  
  const main = document.querySelector("main");
  main.innerHTML += chatHTML;
  renderAssistantResponse();

  const form = document.querySelector(".publisher");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const inputField = document.querySelector(".publisher-input");
    const inputContent = inputField.value;
    inputField.value = '';
    const userMessage = {role: "user", content: inputContent};
    messages.push(userMessage);
    
    renderUserMessage(inputContent);
    renderAssistantResponse();
  })
}

async function getAssistantResposne() {
  const payload = {
    language:  window.language,
    level: window.difficulity,
    messages: messages
  };
  const response = await axiosInstance.post("/assistant", payload);

  return response.data;
}

function renderUserMessage(userMessage) {
  const userHtmlMessage = getUserHtmlMessage(userMessage);
  const chatContent = document.querySelector("#chat-content");
  chatContent.innerHTML += userHtmlMessage;

  // scroll to bottom
  chatContent.scrollTop = chatContent.scrollHeight;
}

async function renderAssistantResponse() {
  const assistantResponse = await getAssistantResposne();
  const assistantMessage = assistantResponse.response;
  messages = assistantResponse.history;
  const assistantMessageObject = {role: "assistant", content: assistantMessage};
  messages.push(assistantMessageObject);
  const assistantHtmlMessage = getAssistantHtmlMessage(assistantMessage);
  const chatContent = document.querySelector("#chat-content");
  chatContent.innerHTML += assistantHtmlMessage;

  // scroll to bottom
  chatContent.scrollTop = chatContent.scrollHeight; 
}

function getAssistantHtmlMessage(message) {
   return `<div class="media media-chat">
              <img class="avatar" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">
              <div class="media-body">
                <p>${message}</p>
              </div>
          </div>`;
}

function getUserHtmlMessage(message) {
  return `<div class="media media-chat media-chat-reverse">
            <div class="media-body">
              <p>${message}</p>
            </div>
          </div>`
}

renderDifficulitySelection();
