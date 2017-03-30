<section class="out_commento">
  <section class="commento">
    <h3>Inserisci un nuovo commento:</h3>
    <form method="post">
      <fieldset name = "Commento">
        <legend>Area Commento</legend>
        <fieldset name="dati personali">
          <legend>Dati Personali</legend>
          <label>First name:
            <input type="text" name="firstname">
          </label>
          <label>Last name:
            <input type="text" name="lastname">
          </label>
        </fieldset>
        <fieldset name="messaggio">
          <legend>Il tuo commento</legend>
          <label>Commento:
            <textarea name="testo" rows="5" cols="40">qui puoi scrivere il tuo commento </textarea>
          </label>
          <label>Voto:
            <input type="range" name="points" min="0" max="10">
          </label>
        </fieldset>
        <input type="submit" value="Submit">
      </fieldset>
    </form>
    <label>Rank del post:
      <meter value="1.0" min="0.0" max="5.0" optimum="5.0">ranked 4/5</meter>
    </label>
  </section>
  <button class ="comment_button" type="button">Mostra area commento</button>
</section>
