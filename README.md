# epd2022

### Tutor Default Credentials:

Email `admin@admin.com`

Password `admin`

### Student Default Credentials

Email `student@student.com`

Password `student`

## Sendmail Configuration

To use the `mail()` function in PHP you will have to configure your `php.ini` and `sendmail.ini` files.

Use [this guide](https://promincproductions.com/blog/xampp-configure-email-to-send-through-gmail/) to configure.

## ToDo

- [ ] Edit documents implementation.
  - `UploadDocumentModal` function.
  - Create backend code to handle document new upload. Do appropriate checks such as:
    - If file is empty, only update the description.
    - If file is not empty, update the description and update the `document` column with the new file in db.
- [ ] Edit projects implementation.
  - Edit project modal in `projects.tpl.php`.
  - `EditProjectModal` function.
  - Create backend code similar to document editing implementation.
- [ ] Index page content.
