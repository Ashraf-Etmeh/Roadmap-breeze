<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Roadmap and Node Creation</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f9;
      }
      .container {
        max-width: 800px;
        margin: 0 auto;
      }
      .form-group {
        margin-bottom: 15px;
      }
      label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
      }
      input,
      textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      button {
        padding: 10px 15px;
        border: none;
        background-color: #28a745;
        color: white;
        cursor: pointer;
        border-radius: 4px;
      }
      button:hover {
        background-color: #218838;
      }
      .loader {
        display: none;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
      }
      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }
      .status {
        margin-top: 15px;
        padding: 10px;
        border-radius: 4px;
      }
      .status.success {
        background-color: #d4edda;
        color: #155724;
      }
      .status.error {
        background-color: #f8d7da;
        color: #721c24;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>Create Roadmap</h2>
      <form id="roadmapForm">
        <div class="form-group">
          <label for="roadmapName">Roadmap Name</label>
          <input type="text" id="roadmapName" name="name" required />
        </div>
        <div class="form-group">
          <label for="roadmapDescription">Description</label>
          <textarea id="roadmapDescription" name="description" required></textarea>
        </div>
        <div style="display: flex; align-items: center; gap: 16px">
          <button type="submit">Create Roadmap</button>
          <div class="loader" id="roadmapLoader"></div>
        </div>
        <div id="roadmapStatus" class="status"></div>
      </form>

      <h2>Create Node</h2>
      <form id="nodeForm">
        <div class="form-group">
          <label for="roadmapId">Roadmap ID</label>
          <input type="text" id="roadmapId" name="roadmap_id" required />
        </div>
        <div class="form-group">
          <label for="parentId">Parent Node ID (Optional)</label>
          <input type="text" id="parentId" name="parent_id" />
        </div>

        <div class="form-group">
          <label for="courseName">Course Name</label>
          <input type="text" id="courseName" name="course_name" required />
        </div>
        <div class="form-group">
          <label for="isOptional">Is Optional</label>
          <input type="checkbox" id="isOptional" name="is_optional" style="width: fit-content" />
        </div>
        <div style="display: flex; align-items: center; gap: 16px">
          <button type="submit">Create Node</button>
          <div class="loader" id="nodeLoader"></div>
        </div>
        <div id="nodeStatus" class="status"></div>
      </form>
    </div>

    <script>
      function handleFormSubmission(formId, url, loaderId, statusId) {
        const form = document.getElementById(formId);
        const loader = document.getElementById(loaderId);
        const statusDiv = document.getElementById(statusId);

        form.addEventListener("submit", function (event) {
          event.preventDefault();
          loader.style.display = "inline-block";
          statusDiv.textContent = "";

          // Collect form data and convert to JSON
          const formData = new FormData(form);
          const jsonData = {};
          formData.forEach((value, key) => {
            if (key === "is_optional") {
              jsonData[key] = form.elements[key].checked; // Handle checkbox separately
            } else {
              jsonData[key] = value;
            }
          });
          if (loaderId == "nodeLoader" && !jsonData?.is_optional) {
            jsonData["is_optional"] = false;
          }
          //   console.log(jsonData);
          fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(jsonData),
          })
            .then((response) => {
              loader.style.display = "none";
              statusDiv.className = "status";
              if (response.ok) {
                statusDiv.classList.add("success");
                statusDiv.textContent = `Success! Status: ${response.status} - ${response.statusText}`;
              } else {
                // console.log(response);
                statusDiv.classList.add("error");
                statusDiv.textContent = `Error! Status: ${response.status} - ${response.statusText}`;
              }
            })
            .catch((error) => {
              loader.style.display = "none";
              statusDiv.className = "status error";
              statusDiv.textContent = `Error: ${error.message}`;
            });
        });
      }

      handleFormSubmission(
        "roadmapForm",
        "http://127.0.0.1:8000/constructor/roadmap/create",
        "roadmapLoader",
        "roadmapStatus"
      );
      handleFormSubmission("nodeForm", "http://127.0.0.1:8000/constructor/node/create", "nodeLoader", "nodeStatus");
    </script>
  </body>
</html>
