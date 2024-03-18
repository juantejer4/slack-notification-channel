<?php

use Illuminate\Notifications\Slack\BlockKit\Composites\ConfirmObject;
use Illuminate\Notifications\Slack\BlockKit\Composites\OptionObject;
use Illuminate\Notifications\Slack\Contracts\ElementContract;

class CheckboxesElement implements ElementContract
{
    /**
     * An identifier for this action.
     *
     * You can use this when you receive an interaction payload to identify the source of the action.
     *
     * Should be unique among all other action_ids in the containing block.
     *
     * Maximum length for this field is 255 characters.
     */
    protected ?string $actionId;

    /**
     * An array of option objects. A maximum of 10 options are allowed.
     */
    protected array $options;

    /**
     * An array of option objects that exactly matches one or more of the options within options.
     * 
     *  These options will be selected when the checkbox group initially loads.
     */
    protected ?array $initialOptions;

    /**
     * A confirm object that defines an optional confirmation dialog that appears after clicking one of the checkboxes in this element.
     */
    protected ?ConfirmObject $confirm; 

    /**
     * Indicates whether the element will be set to auto focus within the view object. Only one element can be set to true. 
     * 
     * Defaults to false.
     */
    protected ?bool $focusOnLoad;

    
    /**
     * Set the action ID of the checkboxes element.
     */
    public function actionId(string $actionId): self
    {
        if(strlen($actionId) > 255) {
            throw new InvalidArgumentException('The maximum length of an action ID is 255 characters.');
        } 
        $this->actionId = $actionId;

        return $this;
    }

    /**
     * Add an option to the options array.
     */
    public function option(string $text, string $value): self
    {
        if(count($this->options) >= 10) {
            throw new InvalidArgumentException('A maximum of 10 options are allowed.');
        }
        $this->options[] = (new OptionObject)->text($text)->value($value);
        return $this;
    }

    /**
     * Set an initial options of the checkboxes element.
     */
    public function initialOption(string $text, string $value): self
    {
        $this->initialOptions[] = (new OptionObject)->text($text)->value($value);
        return $this;
    }

    /**
     * Set the confirm object of the checkboxes element.
     */
    public function confirm(Closure $closure ): self
    {
        $this->confirm = $confirm = new ConfirmObject;
        $closure($confirm);
        return $this;
    }

    /**
     * Set the focus on load of the checkboxes element.
     */
    public function focusOnLoad(bool $focusOnLoad = true): self
    {
        $this->focusOnLoad = $focusOnLoad;
        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'initial_options' => array_map(fn($option) => $option->toArray(), $this->initialOptions),
            'confirm' => $this->confirm?->toArray(),
            'focus_on_load' => $this->focusOnLoad
        ]);

        return array_merge([
            'type' => 'checkboxes',
            'action_id' => $this->actionId,
            'options' => array_map(fn($option) => $option->toArray(), $this->options),
        ], $optionalFields);
    }

}