<?php

namespace SFramework\Config\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlFileLoader loads Yaml config files.
 * Similar to Symfony's Symfony\Component\Routing\Loader\YamlFileLoader.
 */
class YamlFileLoader extends FileLoader
{
    private $yamlParser;

    public function __construct(
        FileLocatorInterface $locator,
        YamlParser $parser = null
    ) {
        $this->yamlParser = $parser;
        parent::__construct($locator);
    }

    /**
     * Loads a yaml file.
     *
     * @param string $file
     * @param null   $type
     *
     * @return array|mixed
     * @throws \Exception
     * @throws \Throwable
     * @throws null
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not found.',
                $path));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        try {
            $parsedConfig = $this->yamlParser->parse(file_get_contents($path),
                Yaml::PARSE_KEYS_AS_STRINGS);
        } catch (ParseException $e) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.',
                $path), 0, $e);
        }

        // empty file
        if (null === $parsedConfig) {
            return [];
        }

        // not an array
        if (!is_array($parsedConfig)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.',
                $path));
        }

        return $parsedConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource)
        && in_array(pathinfo($resource, PATHINFO_EXTENSION),
            array('yml', 'yaml'), true)
        && (!$type || 'yaml' === $type);
    }

}
