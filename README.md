# Shibboleth Authenticator

This REDCap External Module enables the integration of Shibboleth authentication in any survey, public report, public dashboard, or file repository item shared publicly.

## Installation

Install by downloading the latest release zip and unzipping in the modules directory of your REDCap's web server or by downloading directly from the REDCap REPO.

## Configuration

#### System configuration: 

- **Shibboleth Username Login Field**: Name of the server variable that contains the user's username that Shibboleth defines in PHP (e.g. $_SERVER['REMOTE_USER'])
- **Shibboleth Identity Provider**: Repeatable list of Shibboleth Identity Providers. Each entry should contain:
  - **Identity Provider Name**: Enter the name of the Identity Provider (IdP) to be used for authentication
  - **Identity Provider Login Descriptive Text**: Descriptive text displayed with Shibboleth login
  - **Identity Provider Login Image URL**: URL of the image to be displayed with Shibboleth login
  - **Identity Provider URL**: Enter the Login URL of the Identity Provider (IdP) to be used for authentication
  
#### Project configuration:

- **Enable logging**: Check this to enable logging of Shibboleth authentication events in the project's logging module
- **Login message**: Enter a message to be displayed on the login page. This will only be displayed if the project is configured to use multiple Identity Providers
- **Survey subsettings** (repeatable)
  - **Event**: The specific event in which the Shibboleth authentication should be enabled (leave blank to apply to all events)
  - **Survey**: The survey instrument Shibboleth authentication should be integrated with
  - **ID Field**: Optional. This allows the EM to store the username of the person who authenticated. It should be a text field on the survey defined above
  - **Identity Provider Field**: Optional. If you want to store the IdP that the person used to log in, select the field to store it here (it should be a text input field on the same survey)
- **Report**: The public report Shibboleth authentication should be integrated with (repeatable)
- **Dashboard**: The public dashboard Shibboleth authentication should be integrated with (repeatable)
- **File**: The file in the project's file repository that Shibboleth authentication should be integrated with, if sharing that file publicly (repeatable)
- **Folder**: The folder in the project's file repository that Shibboleth authentication should be integrated with, meaning that any files in that folder will have Shibboleth authentication if shared publicly (repeatable)
