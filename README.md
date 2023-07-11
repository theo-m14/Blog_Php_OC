This is a php application under the mvc model created as part of an OpenClassrooms training project.

# Installation

## Create an .env file of the following form in the config folder :

  ```bash
  #BASE_PATH_URL
  BASE_PATH = ""
  
  DB_NAME = ""
  
  DB_HOST = ""
  
  DB_USER = ""
  
  DB_PASSWORD = ""
  
  #APP ENV CAN BE dev or prod, this changes the way errors are returned and displayed.
  APP_ENV = "dev"
  
  #Your SMTP Mail
  MAIL = ""
  ```

## Composer Package installation :

  You need twig/twig and vlucas/phpdotenv

## Convert htaccess on nginx rules if necessary

## For this local projet I was using sendmail coupled with WampServer in order to send mail. You are free to choose the way that suits you best.
