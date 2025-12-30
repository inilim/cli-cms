<?php

declare(strict_types=1);

namespace App\Entity\Attribute;

/**
 * Атрибут для помечание свойств которые дополняют сущность, но основным свойством не является
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AdditionallyAttr {}
