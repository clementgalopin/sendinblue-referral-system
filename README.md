# Sendinblue_Referral_System
Building a user referral system with Sendinblue

This project is a user referral system based on Sendinblue (https://fr.sendinblue.com/) and its v3 API (https://github.com/sendinblue/APIv3-php-library/).

You will find a form in index.php in which you cant submit the email address of the person you want to invite.

You need to submit the referral email address in the page URL (index.php?parrain=EMAIL_ADDRESS).

The form is then submitted to backend.php which interacts with Sendinblue's API and updates the customers' list.
