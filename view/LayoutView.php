<?php

namespace view;

class LayoutView {

public function render($isLoggedIn, LoginView $v, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Login Example</title>
        </head>
        <body>
            <h1>Assignment 2</h1>
            <a href="?register">Register a new user</a>
                ' . $this->renderIsLoggedIn($isLoggedIn) . '
              <div class="container">
                  ' . $v->response() . '

                  ' . $dtv->show() . '
              </div>
          </body>
      </html>';
}

public function renderRegister($isLoggedIn, RegisterView $rv, DateTimeView $dtv) {

    echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Login Example</title>
        </head>
        <body>
            <h1>Assignment 2</h1>
            <a href="/">Back to login</a>
            ' . $this->renderIsLoggedIn($isLoggedIn) . '
            <div class="container">
                ' . $rv->response() . '

                ' . $dtv->show() . '
            </div>
        </body>
        </html>
    ';
}

private function renderIsLoggedIn($isLoggedIn) {

    if ($isLoggedIn) {
        return '
            <h2>Logged in</h2>
        ';
        } else {
            return '
                <h2>Not logged in</h2>
            ';
        }
    }
}
