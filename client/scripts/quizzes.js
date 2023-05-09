import axiosInstance from "../helpers/axiosInstanceClient.js";
import toastr from "https://cdn.jsdelivr.net/npm/toastr@2.1.4/+esm";

const QUIZZ_CATEGORY = [];
const QUIZZ_CATEGORY_ID = [];
const USER_LANGUAGES_NAMES = [];
const USER_LANGUAGES_IDS = [];
const USER_LANGUAGES_PHOTOS = [];
const QUIZZ_ID = [];
const QUIZZ_QUESTION = [];
const QUIZZ_OPTION1 = [];
const QUIZZ_OPTION2 = [];
const QUIZZ_OPTION3 = [];

//This function fetches the user's languages and stores the names,id and photos in their respective arrays
async function getUserLanguages() {
  const response = await axiosInstance.get("/languages/Favorite");

  const languages = response.data;

  languages.forEach((language) => {
    USER_LANGUAGES_NAMES.push(language.languageName);
    USER_LANGUAGES_IDS.push(language.languageId);
    USER_LANGUAGES_PHOTOS.push(language.photoUrl);
  });
}
//This function fetches the quizzes categories and stores the names and ids in their respective arrays
async function getCategoryQuizzes() {
  const response = await axiosInstance.get(
    "http://127.0.0.1:8000/api/categories"
  );

  const quizzes = response.data;

  quizzes.forEach((quizz) => {
    QUIZZ_CATEGORY.push(quizz.name);
    QUIZZ_CATEGORY_ID.push(quizz.id);
  });
}
//This function renders the quizzes category's cards
function getCard(text) {
  /*
      <div class="col">
        <div class="card hoverable px-3 py-5">
          <h2 class="text-center">Grammar</h2>
        </div>
      </div>
    */

  const col = document.createElement("div");
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
//This function creates the language's cards

function createLanguageCard(photo, languageName) {
  const col = document.createElement("div");
  col.className = "col-md-3 mb-4";

  const card = document.createElement("div");
  card.className = "card hoverable h-100";
  card.style = "width: 13rem; height: 10rem;";

  const imgContainer = document.createElement("div");
  imgContainer.className = "d-flex justify-content-center";

  const img = document.createElement("img");
  img.className = "card-img-top rounded-start flags text-center";
  img.src = photo;
  img.alt = `${languageName} flag`;

  imgContainer.appendChild(img);
  card.appendChild(imgContainer);

  const body = document.createElement("div");
  body.className = "card-body d-flex flex-column justify-content-between";

  const title = document.createElement("h5");
  title.className = "card-title text-center mt-3";
  title.innerText = languageName;

  body.appendChild(title);
  card.appendChild(body);

  col.appendChild(card);

  return col;
}
//THis function fetches the languages and creates language cards
async function renderLanguageSelection() {
  await getUserLanguages();
  const main = document.querySelector("main");
  const container = document.createElement("div");
  container.className = "row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4";
  //This loop creates the language cards and adds an event listener to each one
  USER_LANGUAGES_NAMES.forEach((language) => {
    const languageCard = createLanguageCard(
      USER_LANGUAGES_PHOTOS[USER_LANGUAGES_NAMES.indexOf(language)],
      language
    );

    languageCard.addEventListener("click", () => {
      window.language = language;
      container.remove();
      renderQuizz();
    });

    container.appendChild(languageCard);
  });

  main.appendChild(container);
}
//This function renders the quizz category selection
async function renderQuizzCategorySelection() {
  await getCategoryQuizzes();
  const main = document.querySelector("main");
  const container = document.createElement("div");
  container.className = "row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4";
  //This loop creates the quizz category cards and adds an event listener to each one
  QUIZZ_CATEGORY.forEach((category) => {
    const categoryCard = getCard(category);

    categoryCard.addEventListener("click", () => {
      window.category = category;
      container.remove();
      renderLanguageSelection();
    });

    container.appendChild(categoryCard);
  });

  main.appendChild(container);
}
//this function gets the quizz's question and options
async function getQuizzQuestionOptions(languageId, categoryId) {
  const response = await axiosInstance.get(
    `http://127.0.0.1:8000/api/quizz/byCategoryAndLanguage/${categoryId}/${languageId}`
  );
  const quizzes = response.data;
  //This loop stores the quizz's question and options in their respective arrays
  quizzes.forEach((quizz) => {
    QUIZZ_QUESTION.push(quizz.question);
    QUIZZ_ID.push(quizz.id);
    QUIZZ_OPTION1.push(quizz.option.substring(0, quizz.option.indexOf("/")));
    QUIZZ_OPTION2.push(
      quizz.option.substring(
        quizz.option.indexOf("/") + 1,
        quizz.option.lastIndexOf("/")
      )
    );
    QUIZZ_OPTION3.push(
      quizz.option.substring(quizz.option.lastIndexOf("/") + 1)
    );
  });
}
//this function checks the answer and returns whether it's correct or not
async function checkAnswer(quizzId, answer) {
  const data = {
    quizzId: quizzId,
    answer: answer,
  };
  const response = await axiosInstance.post(
    "http://127.0.0.1:8000/api/quizz/check",
    data
  );

  return response.data.Answer;
}
//this function creates the quizz's card
function createQuizzCard(question, option1, option2, option3) {
  const card = document.createElement("div");
  card.className = "card";
  card.style.paddingTop = "2rem";
  card.style.height = "50%";
  const cardBody = document.createElement("div");
  cardBody.className = "card-body";
  cardBody.style.display = "flex";
  cardBody.style.flexDirection = "column";
  cardBody.style.justifyContent = "center";
  const cardTitle = document.createElement("h5");
  cardTitle.className = "card-title";
  cardTitle.innerText = question;
  cardTitle.style.paddingBottom = "2rem;";

  const formGroup = document.createElement("div");
  formGroup.className = "form-group";
  formGroup.style.marginTop = "2rem";
  const formCheck1 = createFormCheck(option1, "option");
  const formCheck2 = createFormCheck(option2, "option");
  const formCheck3 = createFormCheck(option3, "option");
  formGroup.appendChild(formCheck1);
  formGroup.appendChild(formCheck2);
  formGroup.appendChild(formCheck3);
  cardBody.appendChild(cardTitle);
  cardBody.appendChild(formGroup);

  const buttonContainer = document.createElement("div");
  buttonContainer.className = "d-flex justify-content-end";
  const nextButton = createNextButton();
  buttonContainer.appendChild(nextButton);

  card.appendChild(cardBody);
  card.appendChild(buttonContainer);

  const cardContainer = document.createElement("div");
  cardContainer.className = "d-flex justify-content-center big-card";
  cardContainer.appendChild(card);

  return cardContainer;
}
//an assistan function to create the quizz's card
function createFormCheck(labelText, name) {
  const formCheck = document.createElement("div");
  formCheck.className = "form-check";
  const input = document.createElement("input");
  input.className = "form-check-input";
  input.type = "radio";
  input.name = name;
  const label = document.createElement("label");
  label.className = "form-check-label";
  label.innerText = labelText;
  label.style.fontSize = "1.2rem";
  formCheck.appendChild(input);
  formCheck.appendChild(label);
  return formCheck;
}
//this function creates the next button
function createNextButton() {
  const buttonContainer = document.createElement("div");
  buttonContainer.className = "d-flex justify-content-end";
  buttonContainer.style.paddingBottom = "1rem";
  buttonContainer.style.paddingRight = "1rem";

  const nextButton = document.createElement("button");
  nextButton.type = "button";
  nextButton.className = "btn btn-primary";
  nextButton.innerText = "Next";

  buttonContainer.appendChild(nextButton);
  return buttonContainer;
}
//this function renders the quizzes cards and logic
async function renderQuizz() {
  await getQuizzQuestionOptions(
    USER_LANGUAGES_IDS[USER_LANGUAGES_NAMES.indexOf(window.language)],
    QUIZZ_CATEGORY_ID[QUIZZ_CATEGORY.indexOf(window.category)]
  );

  const main = document.querySelector("main");
  const container = document.createElement("div");
  container.className = "row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4";

  // Keep track of the current quiz index
  let currentQuizIndex = 0;

  // Function to render the current quiz
  const renderCurrentQuiz = () => {
    const question = QUIZZ_QUESTION[currentQuizIndex];
    const option1 = QUIZZ_OPTION1[currentQuizIndex];
    const option2 = QUIZZ_OPTION2[currentQuizIndex];
    const option3 = QUIZZ_OPTION3[currentQuizIndex];
    const quizzCard = createQuizzCard(question, option1, option2, option3);
    container.innerHTML = "";
    container.appendChild(quizzCard);
  };

  // Render the first quiz
  renderCurrentQuiz();

  // Add click event listener to the next button
  document.addEventListener("click", async (event) => {
    if (event.target.matches(".btn-primary")) {
      // Check if the answer is correct
      const selectedOption = document.querySelector(
        `input[name=option]:checked`
      );
      if (selectedOption) {
        const answer =
          selectedOption.parentElement.querySelector(
            ".form-check-label"
          ).innerText;
        const isCorrect = await checkAnswer(QUIZZ_ID[currentQuizIndex], answer);
        if (isCorrect) {
          // Move on to the next quiz
          // Display a success message using toastr
          toastr.success("Correct answer!");
          currentQuizIndex++;
          // Check if there are more quizzes
          if (currentQuizIndex < QUIZZ_QUESTION.length) {
            renderCurrentQuiz();
          } else {
            // The user has completed all quizzes
            // Display a success message indicating that you have finished all quizzes using toastr
            toastr.success("Congratulations! You have completed all quizzes.");
            const messageContainer = document.createElement("div");
            messageContainer.className = "d-flex justify-content-center";
            const message = document.createElement("p");
            message.textContent =
              "Congratulations, you have completed the quiz! More quizzes will be coming soon.";
            main.innerHTML = "";
            main.appendChild(message);
          }
        } else {
          // Display an error message using toastr
          toastr.error("Sorry, wrong answer. Please try again.");
        }
      } else {
        // Display an error message if no option is selected using toastr
        toastr.error("Please select an option.");
      }
    }
  });

  main.appendChild(container);
}

renderQuizzCategorySelection();
