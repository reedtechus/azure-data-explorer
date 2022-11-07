<?php

use ReedTech\AzureDataExplorer\AzureDataExplorerApi;

it('can build auth url', function () {
    $de = AzureDataExplorerApi::make('tenantId', 'clientId', 'clientSecret', 'region', 'cluster');
    // Assert that the Auth URL is correct
    expect($de->authUrl())->toBeString('https://login.microsoftonline.com/tenantId/oauth2/token');
});
