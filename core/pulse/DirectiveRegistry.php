<?php

namespace core\pulse;

class DirectiveRegistry
{
    public static function getDirectives(): array
    {
        return [
            '/@if\s*\((.+?)\)/'      => fn($m) => "<?php if ({$m[1]}): ?>",
            '/@elseif\s*\((.+?)\)/'  => fn($m) => "<?php elseif ({$m[1]}): ?>",
            '/@else/'                => fn()   => "<?php else: ?>",
            '/@endif/'               => fn()   => "<?php endif; ?>",
            '/@foreach\s*\((.+?)\)/' => fn($m) => "<?php foreach ({$m[1]}): ?>",
            '/@endforeach/'          => fn()   => "<?php endforeach; ?>",
            '/@php/'                 => fn()   => "<?php ",
            '/@endphp/'              => fn()   => " ?>",
            '/@vite\s*\((.+?)\)/' => fn($m) => "<?php echo \\core\\pulse\\Vite::render({$m[1]}); ?>",
        ];
    }
}
