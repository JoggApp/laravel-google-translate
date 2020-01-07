- After logging in the Google Cloud Platform console you will need to enable the Cloud Translate API via the API Library section.

- Now, we first need to create a new project in the Google Cloud Platform console.

- You will see a screen like this once you click on "New Project" on the dashoard:

<img width="576" alt="screen shot 2018-10-11 at 12 11 19 am" src="https://user-images.githubusercontent.com/11228182/46759117-29c62600-ccec-11e8-99a2-b23ee035a75d.png">

- Select the project you just created, and go the "APIs & Services" -> "Credentials" using the navigation.

- Click at the "Create credentials" dropdown button and choose "API key". To prevent unauthorized use and quota theft, restrict your key to limit how it can be used.

- Now, you can use this key to set a package-specific environment variable:
```
GOOGLE_TRANSLATE_API_KEY=AIzaS.....
```