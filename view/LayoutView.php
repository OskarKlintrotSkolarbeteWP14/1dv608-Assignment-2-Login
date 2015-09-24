<?php
/**
 * Created by Daniel Toll
 */

namespace view;

/**
 * Class LayoutView
 * @package view
 */

class LayoutView {

  /** Renders HTML
   * @param $isLoggedIn
   * @param \view\LoginView $v
   * @param DateTimeView $dtv
   */
  public function render($isLoggedIn, \view\LoginView $v, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response() . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }

  /**
   * Render "Logged in" or "Not logged in" depending on the current state
   *
   * @param $isLoggedIn
   * @return string
   */
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }
}
