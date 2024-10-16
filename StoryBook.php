<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoryBook</title>
    <link rel="stylesheet" href="./StoryBook.css">
</head>

<body>
    <main>
        <header>
            <h1>Sommaire</h1>
            <nav>
                <ul>
                    <li><a href="#titre">Titres</a></li>
                    <li><a href="#corps">Corps</a></li>
                    <li><a href="#couleurs">Couleurs</a></li>
                    <li><a href="#composants">Composants</a></li>
                </ul>
            </nav>
        </header>

        <!-- Section des Titres -->
        <section id="titre">
            <h2>Titres</h2>
            <article>
                <h1>Titre 1</h1>
                <p class="font-semi-bold">Police : Encode Sans - Medium</p>
                <p>Taille de police : <span class="font-semi-bold">48 px</span></p>
                <p>Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <h2>Titre 2</h2>
                <p class="font-semi-bold">Police : Encode Sans - Medium</p>
                <p>Taille de police : <span class="font-semi-bold">32 px</span></p>
                <p>Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <h3>Titre 3</h3>
                <p class="font-semi-bold">Police : Encode Sans - Medium</p>
                <p>Taille de police : <span class="font-semi-bold">28 px</span></p>
                <p>Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <h4>Titre 4</h4>
                <p class="font-semi-bold">Police : Encode Sans - Medium</p>
                <p>Taille de police : <span class="font-semi-bold">24 px</span></p>
                <p>Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <h5>Titre 5</h5>
                <p class="font-semi-bold">Police : Encode Sans - Medium</p>
                <p>Taille de police : <span class="font-semi-bold">20 px</span></p>
                <p>Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
        </section>

        <!-- Section des Corps de texte -->
        <section id="corps">
            <h2>Corps</h2>
            <article>
                <p class="font-semi-bold">Corps / Extra Large</p>
                <p>Police : Inter - SemiBold</p>
                <p>Taille de police : <span class="font-semi-bold">20 px</span></p>
                <br>
                <p class="font-semi-bold large">Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <p class="font-semi-bold">Corps / Extra Large</p>
                <p>Police : Inter - Regular</p>
                <p>Taille de police : <span class="font-semi-bold">20 px</span></p>
                <br>
                <p class="large">Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <p class="font-semi-bold">Corps / Medium</p>
                <p>Police : Inter - SemiBold</p>
                <p>Taille de police : <span class="font-semi-bold">16 px</span></p>
                <br>
                <p class="font-semi-bold medium">Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <p class="font-semi-bold">Corps / Medium</p>
                <p>Police : Inter - Regular</p>
                <p>Taille de police : <span class="font-semi-bold">16 px</span></p>
                <br>
                <p class="medium">Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <p class="font-semi-bold">Corps / Extra Small</p>
                <p>Police : Inter - SemiBold</p>
                <p>Taille de police : <span class="font-semi-bold">12 px</span></p>
                <br>
                <p class="font-semi-bold small">Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
            <article>
                <p class="font-semi-bold">Corps / Extra Small</p>
                <p>Police : Inter - Regular</p>
                <p>Taille de police : <span class="font-semi-bold">12 px</span></p>
                <br>
                <p class="small">Lorem ipsum dolor sit amet.</p>
                <hr>
            </article>
        </section>

        <!-- Section des Couleurs -->
        <section id="couleurs">
            <h2>Couleurs</h2>
            <article>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--white);"></div>
                    <p>white</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--grey1);"></div>
                    <p>grey1</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--grey2);"></div>
                    <p>grey2</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--grey3);"></div>
                    <p>grey3</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--black);"></div>
                    <p>black</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--primary-member);"></div>
                    <p>primary member</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--primary-guest);"></div>
                    <p>primary visitor</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--primary-pro);"></div>
                    <p>primary pro</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--secondary-pro);"></div>
                    <p>secondary pro</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--valide);"></div>
                    <p>valide</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--error);"></div>
                    <p>error</p>
                </div>
                <div class="color-container">
                    <div class="color-block" style="background-color: var(--warning);"></div>
                    <p>warning</p>
                </div>
                <hr>
            </article>
        </section>

        <!-- Section des Composants -->
        <?php
        foreach (glob("components/*/*.php") as $filename) {
            include $filename;
        }
        ?>
        <section id="composants">
            <h2>Composants</h2>
            <div>


                <p>Label:</p>

                
                <p>Input:</p>
                <?php Input::render(type: "text", name: "username", required: true, icon: "./assets/icon/health.svg"); ?>
                <p>Boutton:</p>
                <div class="button-container">
                    <?php Button::render(text: "Button", type: ButtonType::Member, submit: false); ?>
                    <?php Button::render(text: "Button", type: ButtonType::Guest, submit: false); ?>
                    <?php Button::render(text: "Button", type: ButtonType::Pro, submit: false); ?>
                </div>

                <p>Boutton (Toast):</p>
                <?php Button::render(text: "Clique Moi ;)", type: ButtonType::Member, onClick: "renderToast('Toast !', 'success')"); ?>
                <?php Toast::render("Toast !", ToastType::SUCCESS); ?>
                <p>Checkbox:</p>
                <?php CheckBox::render(class: "my-checkbox", id: "subscribe", name: "subscribe", required: true, checked: false, text: "Subscribe to newsletter"); ?>
                <p>ImagePicker:</p>

                <p>Range:</p>
                
                <p>barre de filtres:</p>
                <p>SearchBar:</p>
                <p>Card:</p>
                <p>Card Tel:</p>
                
                <p>Footer:</p>
                <p>Header:</p>



                <?php include '/component/Input/Input.php' ?>



                <?php Input::render(type: "email", name: " gros caca"); ?>

            </div>
            <hr>
        </section>
    </main>
</body>

</html>