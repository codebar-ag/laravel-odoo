<?php

use CodebarAg\Odoo\Requests\Api\Fields\GetFieldsRequest;
use CodebarAg\Odoo\Requests\Api\Models\CallMethodRequest;
use CodebarAg\Odoo\Requests\Api\Models\CreateRequest;
use CodebarAg\Odoo\Requests\Api\Models\SearchReadRequest;
use CodebarAg\Odoo\Requests\Api\Models\WriteRequest;
use CodebarAg\Odoo\Requests\Api\Permissions\GetPermissionsRequest;

it('keeps regular model and method names unchanged in the endpoint', function () {
    expect((new SearchReadRequest('res.partner'))->resolveEndpoint())->toBe('/json/2/res.partner/search_read')
        ->and((new CreateRequest('sale.order', []))->resolveEndpoint())->toBe('/json/2/sale.order/create')
        ->and((new WriteRequest('sale.order', [1], []))->resolveEndpoint())->toBe('/json/2/sale.order/write')
        ->and((new GetFieldsRequest('account.analytic.line'))->resolveEndpoint())->toBe('/json/2/account.analytic.line/fields_get')
        ->and((new GetPermissionsRequest('res.partner', 'read'))->resolveEndpoint())->toBe('/json/2/res.partner/has_access')
        ->and((new CallMethodRequest('sale.order', 'action_confirm'))->resolveEndpoint())->toBe('/json/2/sale.order/action_confirm');
});

it('encodes model and method names so they cannot escape their path segment', function () {
    expect((new SearchReadRequest('res.partner/../../web/database/list?x='))->resolveEndpoint())
        ->toBe('/json/2/res.partner%2F..%2F..%2Fweb%2Fdatabase%2Flist%3Fx%3D/search_read')
        ->and((new CreateRequest('a/b', []))->resolveEndpoint())->toBe('/json/2/a%2Fb/create')
        ->and((new WriteRequest('a#b', [1], []))->resolveEndpoint())->toBe('/json/2/a%23b/write')
        ->and((new GetFieldsRequest('a?b'))->resolveEndpoint())->toBe('/json/2/a%3Fb/fields_get')
        ->and((new GetPermissionsRequest('a/b', 'read'))->resolveEndpoint())->toBe('/json/2/a%2Fb/has_access')
        ->and((new CallMethodRequest('sale.order', 'unlink/../x'))->resolveEndpoint())->toBe('/json/2/sale.order/unlink%2F..%2Fx');
});
