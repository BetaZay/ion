<?php

namespace core\pulse;

use core\logging\Logger;
use ReflectionException;

class Component
{
    protected static array $slots = [];

    protected static array $active = [];

    /**
     * @throws ReflectionException
     */
    public static function render(string $name, array $data, callable $body): void
    {
        $class = 'App\\View\\Components\\' . self::toClassName($name);
        $view  = "resources/views/components/{$name}.pulse.php";

        if (in_array($name, self::$active)) {
            echo "<!-- Component '$name' render loop detected -->";
            return;
        }

        self::$active[] = $name;

        if (!file_exists(base_path($view))) {
            Logger::error("Component view file not found: $view");
            echo "<!-- View not found: $view -->";
            return;
        }

        if (class_exists($class)) {
            $instance = self::instantiate($class, $data);
            $view = $instance->render(); // returns full path or view name
            $data = array_merge($data, method_exists($instance, 'data') ? $instance->data() : []);
        }

        ob_start();
        $body();
        $data['slot'] = ob_get_clean();
        $data['slots'] = self::$slots;
        self::$slots = [];

        Logger::info("Rendering component: $name, file: $view");
        Logger::info("Component data: " . json_encode($data));
        Logger::info("Slots: " . json_encode(array_keys(self::$slots)));

        View::renderFile(base_path($view), $data);

        array_pop(self::$active);
    }

    public static function slot(string $name, callable $body): void
    {
        ob_start();
        $body();
        self::$slots[$name] = ob_get_clean();
    }

    protected static function toClassName(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }

    /**
     * @throws ReflectionException
     */
    protected static function instantiate(string $class, array $data)
    {
        $ref = new \ReflectionClass($class);
        $params = [];

        foreach ($ref->getConstructor()?->getParameters() ?? [] as $param) {
            $paramName = $param->getName();
            $params[] = $data[$paramName] ?? $param->getDefaultValue();
        }

        return $ref->newInstanceArgs($params);
    }
}
