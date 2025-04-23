# EntraId Authenticator

This REDCap External Module enables the integration of Entra ID authentication in any survey, public report, public dashboard, or file repository item shared publicly.

## Installation

Install by downloading the latest release zip and unzipping in the modules directory of your REDCap's web server or by downloading directly from the REDCap REPO.

## Configuration

#### System configuration: 

- **Domain**: Domain for accounts on this Entra ID Site</strong>:<br>Typically this is the domain of the primary email address associated with accounts at this site (e.g., `yale.edu`)
- **AD Tenant ID**: Entra ID AD Tenant ID
- **Client ID**: Entra ID Client ID
- **Client Secret**: Entra ID Client Secret
- **Redirect URL**: Entra ID Redirect URL
  
#### Project configuration:

- **Enable logging**: Check this to enable logging of Entra ID authentication events in the project's logging module
- **Survey subsettings** (repeatable)
  - **Event**: The specific event in which the Entra ID authentication should be enabled (leave blank to apply to all events)
  - **Survey**: The survey instrument Entra ID authentication should be integrated with
  - **ID Field**: Optional. This allows the EM to store the username of the person who authenticated. It should be a text field on the survey defined above
- **Report**: The public report Entra ID authentication should be integrated with (repeatable)
- **Dashboard**: The public dashboard Entra ID authentication should be integrated with (repeatable)
- **File**: The file in the project's file repository that Entra ID authentication should be integrated with, if sharing that file publicly (repeatable)
- **Folder**: The folder in the project's file repository that Entra ID authentication should be integrated with, meaning that any files in that folder will have Entra ID authentication if shared publicly (repeatable)
