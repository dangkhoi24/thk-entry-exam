## Entrance Exam for THK Holdings Vietnam in Hanoi

Welcome to THK Holdings Vietnam in Hanoi!!
Thank you for applying to us!

## About this exam

In this exam, you implement for these following fields using a Laravel application.
There are required and optional field to implement.

If you implement optional field, we consider your extra technical skills.

We will use Laravel Sail to build this exam environment.
Laravel Sail is a Laravel library that allows you to build an environment using virtualization container software called docker just by typing a command.
For more information about Laravel Sail, please refer to [the official Laravel documentation](https://readouble.com/laravel/11.x/en/sail.html).

### Application's information

[view](document/INFORMATION.md)

### Implementation fields

[view](document/IMPLEMENTATION_FIELD.md)

### Notice

- You write some comments as optional. But if you do, please write in Japanese or English.
- The design (CSS style) is up to you. Please use SCSS as possible.
- If there is unclear stated and specification, you implement the best for you.
- We will consider for the selection process from all of your implemented contents.
- Even if you couldn't complete all items, no problem. We will consider only your implemented scope. So please submit it by deadline.

### How to submit this exam

Please compress the `entry_test` folder into a zip file and submit it via email.
To reduce data size, please delete the `entry_exam/node_modules` folder and `entry_exam/vendor` folder along with all files under them before compressing.

### Any comments (optional)

If you have any comments about this exam, please write them down here.
```
Thank you for reviewing. All required fields and both optional fields are implemented.

[Setup]
- Run `./vendor/bin/sail php artisan migrate --seed` (adds the `bookings` table and 50 sample bookings).
- New npm packages (flatpickr, tom-select) are installed by the vite container (`npm install` on start).
- The admin screens are translated with Laravel localization (lang/ja, lang/en).
  `APP_LOCALE=ja` is set in .env, and the language can be switched (日本語 / English) at the bottom of the side menu.

[Hotel search]
- Partial match on the hotel name (`%` and `_` are escaped) and a prefecture select box.
- Submitting an empty form shows "何も入力されていません" in red right below the form.
- The result route was changed from POST to GET, so the conditions survive reload / delete / paging.
- Results are paginated (20 per page).

[Hotel create]
- Validation: name required (max 255), prefecture required (must exist), image required
  (jpeg/png/webp, max 2MB). The image is required because the user screens always show it.
- After registering, the empty form is shown again with a success message (to add the next one).

[Hotel edit] input -> confirm -> complete
- The confirm screen is its own route (/admin/hotel/edit/confirm). It is shown as a dialog over the
  input form and lists only the changed items (before -> after).
- The entered content is kept in the session, so "Cancel" goes back to the input with the values kept.
- A new image is saved under public/assets/img/hotel/tmp first and moved to public/assets/img/hotel
  only when "Update" is pressed (the old uploaded image is deleted). Sample images are never deleted.
- The save button is enabled only after something is changed; if nothing changed, the input screen is
  shown again with a message instead of the confirm screen.

[Hotel delete]
- A confirmation dialog (opened with JavaScript) asks before deleting; the hotel is deleted only by its "削除" button.
- A hotel that has bookings cannot be deleted (foreign key), a message is shown instead.

[Bookings (optional)]
- The ERD column `chekin_time` is treated as a typo and named `checkin_time`.
- Check-in / check-out are searched by date (a date picker), not by exact date and time.

[Back-end]
- Validation in Form Requests (app/Http/Requests/Admin), messages in lang/{ja,en}/validation.php.
- Image files are handled by App\Services\HotelImageService on the "hotel_images" disk (public/assets/img).
  Files and DB are kept consistent: a stored image is removed again if the DB write fails,
  and an old image is deleted only after the record was updated / deleted.
- The edit content between the screens is App\Support\HotelEditDraft (kept in the session).
- Search conditions are model scopes (partial match with escaped wildcards, prefecture).
- Create / update / delete write logs (storage/logs/laravel.log) with the handler name and hotel id;
  failures are logged as errors before the exception is re-thrown.
- The route URLs / names given in the exam were kept as they are.

[Tests]
- `./vendor/bin/sail artisan test` : feature tests for hotel search / create / edit / delete and booking search
  (tests/Feature/Admin, run on the "testing" database created by Sail).

[Front-end]
- SCSS: design tokens, typography, utility classes and components (resources/scss).
- Blade components: x-button, x-input, x-select, x-form-field, x-modal, x-toast and page templates
  (x-templates.default-page / list-page / form-page).
- Responsive: the side menu becomes a top bar with a menu button on small screens.

[Not changed]
- On the user top page, some prefecture links in the original code are wrong
  (e.g. /gukushima/hotellist returns an error). They were left as they are because they are outside the scope.
```
