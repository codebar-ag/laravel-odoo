<?php

declare(strict_types=1);

namespace CodebarAg\Odoo\Data\Enums;

/**
 * Which half of an Odoo relation tuple a property should receive.
 *
 * Odoo serialises a many2one relation as a two-element tuple [id, display_name]
 * (or `false` when empty). We expose those as two flat properties, e.g.
 * `projectId` + `projectName`, each reading the same input key.
 */
enum RelationPart
{
    case Id;
    case Name;
}
