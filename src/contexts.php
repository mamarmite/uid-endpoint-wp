<?php
namespace Mamarmite\UIDEndpoint;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

const CLIENT_CONTEXT_DEFAULT = "default";
const CLIENT_CONTEXT_HTML_HEAD = "client-side-html-head";
const CLIENT_CONTEXT_JSON = "client-side-json";
const CLIENT_CONTEXT_BROWSER_PREVIEW = "client-side-html-json-preview";


// need to adjust some value depending on context.
// Creative Work
//  when CONTEXT_HTML_HEAD : url contains the target entity
//  when CONTEXT_JSON : url contains the target entity
//  when CONTEXT_BROWSER_PREVIEW : url contains the target entity
//  when CONTEXT_HTML_HEAD : sameEntityOfPage contains the ?
//  when CONTEXT_JSON : sameEntityOfPage contains the target entity
//  when CONTEXT_BROWSER_PREVIEW : url contains the target entity

