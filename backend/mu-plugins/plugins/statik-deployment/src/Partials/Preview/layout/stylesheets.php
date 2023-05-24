<?php

declare(strict_types=1);

\defined('WPINC') || exit;

?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap"
      rel="preload" as="style" onload="this.rel='stylesheet'">
<style>
    html {
        --color-primary: #2d333a;
        --color-secondary: #31d994;
        --color-active: #6779fa;
        --font-family: ulp-font, -apple-system, BlinkMacSystemFont, Roboto, Helvetica, sans-serif;
        font-size: 10px;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        min-height: calc(100vh - 32px);
        padding: 0;
        margin: 0;
        font-family: var(--font-family);
        background: #f1f1f1;
        position: relative;
    }

    body #wpadminbar * {
        font-family: var(--font-family);
    }

    body #wpadminbar #wp-toolbar > ul {
        background: #1d2327;
    }

    .wrapper {
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: calc(100vh - 200px);
        padding: 4px 0 50px;
        width: 100vw;
    }

    main {
        display: flex;
        flex-direction: column;
        min-height: 420px;
        padding: 60px 40px;
        position: relative;
        text-align: center;
        max-width: 420px;
    }

    header {
        margin: 0 0 40px 0;
    }

    header img {
        height: 35px;
        max-width: 100%;
    }

    h1 {
        color: var(--color-primary);
        font-family: var(--font-family);
        font-size: 2.4em !important;
        font-style: normal;
        font-weight: 400 !important;
        line-height: 3.6rem !important;
        margin: 24px 0 16px;
        word-break: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        max-height: 11rem;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    h1 strong {
        font-size: 1.2em;
        color: var(--color-active);
    }

    p, .wp-die-message {
        font-family: var(--font-family);
        font-style: normal;
        font-weight: 400;
        font-size: 1.4em;
        line-height: 2.1rem;
        color: var(--color-primary);
        margin: 0 0 1.5rem 0;
    }

    p::first-letter {
        text-transform: uppercase;
    }

    code {
        margin: 10px 0;
        background: #efefef;
        padding: 5px;
        border: solid 1px #D5D8DE;
        border-radius: 5px;
        display: block;
        white-space: pre;
        font-size: 1.4rem;
        text-align: left;
        overflow: scroll;
    }

    code.domain {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .message {
        background: #a2ca8a;
        border-left: 4px solid #20a01e;
        padding: 5px 10px;
        color: var(--color-primary);
        border-radius: 4px;
        opacity: 1;
        font-size: 1.2rem;
        text-align: left;
        word-break: break-word;
    }

    .message.error {
        background: #ff8484;
        border-left: 4px solid #fc1e12;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    .buttons a {
        display: inline-flex;
        background: transparent;
        font-weight: bold;
        border: solid 1px #D5D8DE;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-direction: row;
        padding: 10px 15px;
        font-size: 1.5rem;
        color: var(--color-primary);
        transition: all 200ms;
        box-sizing: border-box;
    }

    .buttons a:hover {
        border-color: var(--color-active);
        color: var(--color-active);
    }

    .buttons a[data-deprecated] {
        background: #fff1e4;
        position: relative;
    }

    .buttons a[data-deprecated]:after {
        content: attr(data-deprecated);
        background: #d57d12;
        padding: 3px 8px;
        border-radius: 10px;
        color: white;
        font-size: 0.6em;
        position: absolute;
        right: -10px;
        top: -10px;
        transition: all 200ms;
    }

    .buttons a[data-deprecated]:hover:after {
        background: var(--color-active);
    }

    .buttons a img {
        max-height: 2.0rem;
    }

    .buttons a .dashicons {
        transition: initial;
    }

    footer {
        font-size: 0.8rem;
        margin: 1.125rem 0 0;
        color: var(--color-primary);
        opacity: 0.6;
        text-align: center;
        position: absolute;
        bottom: 1rem;
        width: 100%;
    }

    @media screen and (max-width: 782px) {
        body {
            min-height: calc(100vh - 46px);
        }

        main {
            padding: 40px;
        }
    }

    @media screen and (min-width: 783px) {
        body {
            align-items: center;
        }

        .wrapper {
            min-height: calc(100vh - 200px);
            padding: 18px 0 50px;
            justify-content: center;
        }

        main {
            padding: 60px 40px;
        }
    }
</style>