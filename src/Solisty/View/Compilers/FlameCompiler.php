<?php

namespace Solisty\View\Compilers;

use Solisty\String\Str;

class FlameCompiler
{
    private array $compilers = [];
    private string $content = "";

    public function compileString(string $content): void
    {
        $this->content = $content;
        $regex = "/(?:(#([a-z]+\s*))(\((.*)\)))|{%(.*)%}|({'(.*)'})|{!(.*)!}/im";
        preg_match_all($regex, $content, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL);

        foreach ($matches as $match) {
            $match[0] = $match[0];
            $match[1] = $match[2];
            $match[2] = $match[3];
            $match[3] = $match[4];

            // TODO: store uncompiled directives for error reporting

            $this->handleDirective($match);
        }

        $this->saveCacheFileContent();
    }

    public function handleDirective($match)
    {
        $callback = '';
        if (Str::endsWith($match[0], "{'") && Str::endsWith($match[0], "'}")) {
            $callback = $this->compilers['comment'];
            $match[3] = Str::trim($match[7]);
        } else if (Str::startWith($match[0], "{%") && Str::endsWith($match[0], "%}")) {
            $callback = $this->compilers['echo'];
            $match[3] = Str::trim($match[5]);
        } else if (Str::startWith($match[0], "{!") && Str::endsWith($match[0], "!}")) {
            $callback = $this->compilers['unsafe_echo'];
            $match[3] = Str::trim($match[8]);
        } else {
            $callback = $this->compilers[$match[1]];
        }

        $this->replaceFirstStatement($match, $callback);
    }

    public function replaceFirstStatement(array $match, string $callback)
    {
        // find the next occurence: begin
        $next_pos = strpos($this->content, $match[0]);
        if ($next_pos !== false) {
            $compiled = $this->{$callback}($match[3]);
            $this->content = Str::replaceAt($this->content, $compiled, $next_pos, strlen($match[0]));

            $compiled_end = $this->replace_end($match[1]);

            if (!empty($compiled_end)) {
                $str_end = '#end' . $match[1];
                $next_pos = Str::firstOffsetOf($this->content, $str_end);

                if ($next_pos !== false) {
                    $this->content = Str::replaceAt($this->content, $compiled_end, $next_pos, strlen($str_end));
                }
            }
        }
    }


    public function getCachedFilePath(): string
    {
        return "compiled.php";
    }

    public function addDirectiveHandler(string $directive, string $callback)
    {
        $this->compilers[$directive] = $callback;
    }

    public function registerBuiltinDirectives()
    {
        $directives = ['if', 'foreach', 'php'];
        $enclosed = [
            ["{'", "'}", 'comment'],
            ["{%", "%}", 'echo'],
            ["{!", "!}", 'unsafe_echo'],
        ];

        foreach ($directives as $directive) {
            $callback = 'replace_' . $directive;
            if (method_exists($this, $callback)) {
                $this->addDirectiveHandler($directive, $callback);
            }
        }

        foreach ($enclosed as $enc) {
            $callback = 'replace_' . $enc[2];
            if (method_exists($this, $callback)) {
                $this->addDirectiveHandler($enc[2], $callback);
            }
        }
    }

    public function saveCacheFileContent()
    {
        file_put_contents($this->getCachedFilePath(), $this->content);
    }

    private function replace_if($expression)
    {
        return "<?php if($expression): ?>";
    }

    private function replace_foreach($expression)
    {
        return "<?php foreach($expression): ?>";
    }

    private function replace_php($code)
    {
        return "<?php $code ?>";
    }

    private function replace_comment($content)
    {
        return "<!-- $content -->";
    }

    private function replace_echo($expression)
    {
        return "<?php echo htmlspecialchars($expression); ?>";
    }

    private function replace_unsafe_echo($expression)
    {
        return "<?php echo $expression; ?>";
    }

    private function replace_end($directive)
    {
        switch ($directive) {
            case 'if':
                return '<?php endif; ?>';
            case 'foreach':
                return '<?php endforeach; ?>';
            default:
                return null;
        }
    }

    public function getCompiledContent(): string {
        return $this->content;
    }
}
