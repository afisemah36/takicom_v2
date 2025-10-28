<?php

/**
 * Définition des routes
 */

// Page d'accueil
$router->get('/', 'HomeController@index');

// Authentification
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Inscription
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');

// Dashboard
$router->get('/dashboard', 'DashboardController@index');

// Clients
$router->get('/clients', 'ClientController@index');
$router->get('/clients/create', 'ClientController@create');
$router->post('/clients', 'ClientController@store');
$router->get('/clients/{id}', 'ClientController@show');
$router->get('/clients/{id}/edit', 'ClientController@edit');
$router->post('/clients/{id}/update', 'ClientController@update');
$router->post('/clients/{id}/delete', 'ClientController@delete');

// Fournisseurs
$router->get('/fournisseurs', 'FournisseurController@index');
$router->get('/fournisseurs/create', 'FournisseurController@create');
$router->post('/fournisseurs', 'FournisseurController@store');
$router->get('/fournisseurs/{id}', 'FournisseurController@show');
$router->get('/fournisseurs/{id}/edit', 'FournisseurController@edit');
$router->post('/fournisseurs/{id}/update', 'FournisseurController@update');
$router->post('/fournisseurs/{id}/delete', 'FournisseurController@delete');

// Articles
$router->get('/articles', 'ArticleController@index');
$router->get('/articles/create', 'ArticleController@create');
$router->post('/articles', 'ArticleController@store');
$router->get('/articles/{id}', 'ArticleController@show');
$router->get('/articles/{id}/edit', 'ArticleController@edit');
$router->post('/articles/{id}/update', 'ArticleController@update');
$router->post('/articles/{id}/delete', 'ArticleController@delete');

// Catégories
$router->get('/categories', 'CategorieController@index');
$router->post('/categories', 'CategorieController@store');
$router->post('/categories/{id}/update', 'CategorieController@update');
$router->post('/categories/{id}/delete', 'CategorieController@delete');

// Stock
$router->get('/stock', 'StockController@index');
$router->get('/stock/{id}', 'StockController@show');
$router->post('/stock/{id}/update', 'StockController@update');

// Devis
$router->get('/devis', 'DevisController@index');
$router->get('/devis/create', 'DevisController@create');
$router->post('/devis', 'DevisController@store');
$router->get('/devis/{id}', 'DevisController@show');
$router->get('/devis/{id}/edit', 'DevisController@edit');
$router->post('/devis/{id}/update', 'DevisController@update');
$router->post('/devis/{id}/delete', 'DevisController@delete');
$router->get('/devis/{id}/pdf', 'DevisController@generatePdf');

// Factures Client
$router->get('/factures', 'FactureClientController@index');
$router->get('/factures/create', 'FactureClientController@create');
$router->post('/factures', 'FactureClientController@store');
$router->get('/factures/{id}', 'FactureClientController@show');
$router->get('/factures/{id}/edit', 'FactureClientController@edit');
$router->post('/factures/{id}/update', 'FactureClientController@update');
$router->post('/factures/{id}/delete', 'FactureClientController@delete');
$router->get('/factures/{id}/pdf', 'FactureClientController@generatePdf');

// Factures Fournisseur
$router->get('/factures-fournisseur', 'FactureFournisseurController@index');
$router->get('/factures-fournisseur/create', 'FactureFournisseurController@create');
$router->post('/factures-fournisseur', 'FactureFournisseurController@store');
$router->get('/factures-fournisseur/{id}', 'FactureFournisseurController@show');
$router->get('/factures-fournisseur/{id}/edit', 'FactureFournisseurController@edit');
$router->post('/factures-fournisseur/{id}/update', 'FactureFournisseurController@update');
$router->post('/factures-fournisseur/{id}/statut', 'FactureFournisseurController@changerStatut');
$router->post('/factures-fournisseur/{id}/delete', 'FactureFournisseurController@delete');
$router->get('/factures-fournisseur/{id}/imprimer', 'FactureFournisseurController@imprimer');

// API Routes (pour AJAX)
$router->get('/api/articles/search', 'ApiController@searchArticles');
$router->get('/api/clients/search', 'ApiController@searchClients');
$router->get('/api/fournisseurs/search', 'ApiController@searchFournisseurs');

// Configuration
$router->get('/parametres', 'EntrepriseController@index');
$router->post('/parametres/update', 'EntrepriseController@update');
$router->post('/parametres/delete-logo', 'EntrepriseController@deleteLogo');
