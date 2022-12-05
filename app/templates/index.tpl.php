<?php if (!defined('ACCESS')) exit; ?>

<?= Account::LoginRequired(); ?>

<h1>
  <i class="<?= $iconClass; ?>"></i>
  <?= $title; ?>
</h1>

<div>
  <p>
    Καλώς ορίσατε το project του μαθήματος Εκπαιδευτικά Περιβάλλοντα Διαδικτύου για την ανάπτυξη ενός συστήματος διαχείρισης ενός μαθήματος.
  </p>
  <p>
    Το backend της ιστοσελίδας υλοποιήθηκε με τη χρήση της PHP και της MySQL.
    Πιο συγκεκριμένα, δημιουργήθηκε ένα απλό CMS με κλάσεις και μεθόδους (δείτε <code>app/classes</code>)
    για την επικοινωνία με τη βάση δεδομένων και την υλοποίηση του template system (δείτε <code>app/templates</code>).
    <p />

    Ως προς το frontend χρησιμοποιήθηκε
    <a href="https://getbootstrap.com/" target="_blank">Bootstrap 5</a> μαζί με Bootstrap Icons,
    <a href="https://jquery.com/" target="_blank">jQuery</a>
    και <a href="https://datatables.net/" target="_blank">DataTables</a>
    για διαδραστικότητα, το responsiveness και γενικότερα ένα ευχάριστο User Experience.
    <p />
    
    Για τη βάση δεδομένων δημιουργήθηκαν οι παρακάτω πίνακες:
    <div class="table-responsive">
      <table class="table">
        <tbody>
          <tr>
            <th>announcements</th>
            <td>
              id
              <br />
              <code>int <i class="bi bi-key"></i></code>
            </td>
            <td>
              title
              <br />
              <code>varchar(255)</code>
            </td>
            <td>
              body
              <br />
              <code>text</code>
            </td>
            <td>
              is_project
              <br />
              <code>int</code>
            </td>
            <td>
              projectId
              <br />
              <code>int</code>
            </td>
            <td>
              creation_date
              <br />
              <code>date</code>
            </td>
          </tr>
          <tr>
            <th>documents</th>
            <td>
              id
              <br />
              <code>int <i class="bi bi-key"></i></code>
            </td>
            <td>
              document
              <br />
              <code>mediumblob</code>
            </td>
            <td>
              description
              <br />
              <code>text</code>
            </td>
            <td>
              filename
              <br />
              <code>varchar(255)</code>
            </td>
            <td>
              extension
              <br />
              <code>varchar(30)</code>
            </td>
            <td>
              project_id
              <br />
              <code>int</code>
            </td>
            <td>
              creation_date
              <br />
              <code>date</code>
            </td>
          </tr>
          <tr>
            <th>projects</th>
            <td>
              id
              <br />
              <code>int <i class="bi bi-key"></i></code>
            </td>
            <td>
              title
              <br />
              <code>varchar(255)</code>
            </td>
            <td>
              body
              <br />
              <code>text</code>
            </td>
            <td>
              deadline_date
              <br />
              <code>date</code>
            </td>
            <td>
              creation_date
              <br />
              <code>date</code>
            </td>
          </tr>
          <tr>
            <th>users</th>
            <td>
              id
              <br />
              <code>int <i class="bi bi-key"></i></code>
            </td>
            <td>
              firstname
              <br />
              <code>varchar(32)</code>
            </td>
            <td>
              lastname
              <br />
              <code>varchar(32)</code>
            </td>
            <td>
              email
              <br />
              <code>varchar(320)</code>
            </td>
            <td>
              password
              <br />
              <code>varchar(32)</code>
            </td>
            <td>
              salt
              <br />
              <code>varchar(32)</code>
            </td>
            <td>
              creation_date
              <br />
              <code>date</code>
            </td>
            <td>
              role
              <br />
              <code>int</code>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </p>
  <p>
    Η βασική ιδέα είναι τα δεδομένα να καλούνται μέσω AJAX (δείτε <code>ajax.php</code> και <code>js/main.js</code>)
    από τον server και να εμφανίζονται στην οθόνη χωρίς να γίνεται ανανέωση της σελίδας.
    Δεν χρησιμοποιήθηκε κάποιο framework για την υλοποίηση του backend, αλλά μόνο οι βασικές βιβλιοθήκες της PHP,
    με μια εξαίρεση της βιβλιοθήκης <a href="https://github.com/jbowens/jBBCode" target="_blank">JBBCode</a> για την εμφάνιση των κειμένων με BBCode,
    η οποία στο τελικό project δεν χρησιμοποιήθηκε καθώς δεν ήταν απαραίτητη.
  </p>
  <p>
    Υλοποιήθηκαν οι εξής σελίδες:
  <ul>
    <li>
      <a href="<?= BASE_URL; ?>/?page=index">Αρχική</a>
    </li>
    <li>
      <a href="<?= BASE_URL; ?>/?page=login">Σύνδεση</a>
    </li>
    <li>
      <a href="<?= BASE_URL; ?>/?page=announcements">Ανακοινώσεις</a>
      <ul>
        <li>
          Οι διδάσκοντες μπορούν να δημιουργούν, να διαγράφουν και να επεξεργάζονται ανακοινώσεις.
        </li>
      </ul>
    </li>
    <li>
      <a href="<?= BASE_URL; ?>/?page=documents">Έγγραφα</a>
      <ul>
        <li>
          Οι διδάσκοντες μπορούν να δημιουργούν, να διαγράφουν και να επεξεργάζονται έγγραφα.
        </li>
        <li>
          Τα έγγραφα πρέπει να τελειώνουν με κάποια από τις επεκτάσεις που υποστηρίζονται.
          Οι επεκτάσεις που υποστηρίζονται είναι:
          <br />
          <code>pdf</code>,
          <code>sql</code>,
          <code>doc</code>,
          <code>docx</code>,
          <code>odt</code>,
          <code>txt</code>,
          <code>rtf</code>,
          <code>xls</code>,
          <code>xlsx</code>,
          <code>zip</code>,
          <code>rar</code>,
          <code>7z</code>.
          <br />
          Μπορείτε να αλλάξετε τις διαθέσιμες επεκτάσεις τροποποιόντας τη μεταβλητή <code>$allowed_extensions</code>
          στο αρχείο <code>app/classes/documents.class.php</code>.
        </li>
        <li>
          Τα έγγραφα αποθηκεύονται στη βάση δεδομένων σε μορφή <code>mediumblob</code>
          ώστε να μην χρειάζεται να αποθηκεύονται στον server και να είναι accessible με direct link.
          Το μέγιστο μέγεθος του αρχείου είναι 1MB, το οποίο μπορεί να αλλάξει επεξεργάζοντας το αρχείο <code>php.ini</code>
          και τη μεταβλητή <code>MAX_FILE_SIZE</code> στο αρχείο <code>app/configuration.php</code>.
        </li>
      </ul>
    </li>
    <li>
      <a href="<?= BASE_URL; ?>/?page=projects">Εργασίες</a>
      <ul>
        <li>
          Οι διδάσκοντες μπορούν να δημιουργούν, να διαγράφουν και να επεξεργάζονται εργασίες.
        </li>
        <li>
          Κατά τη δημιουργία μιας εργασίας, αυτόματα θα δημιουργηθεί και η αντίστοιχη ανακοίνωση
          και θα προστεθεί το έγγραφο (εκφώνηση) της εργασίας.
        </li>
        <li>
          Κατά τη διαγραφή μιας εργασίας, αυτόματα θα διαγραφεί και η αντίστοιχη ανακοίνωση
          και θα διαγραφεί το έγγραφο (εκφώνηση) της εργασίας, αν φυσικά υπάρχουν.
        </li>
      </ul>
    </li>
    <li>
      <a href="<?= BASE_URL; ?>/?page=manage-users">Διαχείριση χρηστών</a> <small class="text-muted font-small"><i>(μόνο για διδάσκοντες)</i></small>
      <ul>
        <li>Οι διδάσκοντες μπορούν να δημιουργούν, να διαγράφουν και να επεξεργάζονται χρήστες.</li>
        <li>Προβολή με τη χρήση DataTables.</li>
      </ul>
    </li>
  </ul>
  </p>

</div>