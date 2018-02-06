# Sendinblue_Referral_System
Building a user referral system with Sendinblue

This project is a user referral system based on Sendinblue (https://fr.sendinblue.com/).

You will find a form in index.php in which you cant submit the email address of the person you want to invite.

You need to submit the referral email address in the page URL (index.php?parrain=EMAIL_ADDRESS).

The form is then submitted to backend.php which interacts with Sendinblue's API, updates the customers' list and triggers an invitation email. 
