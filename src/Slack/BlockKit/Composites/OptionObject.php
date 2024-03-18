<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Notifications\Slack\Contracts\ObjectContract;
use InvalidArgumentException;

class OptionObject implements ObjectContract
{
    /**
     * A text object that defines the text shown in the option on the menu.
     *  
     * Overflow, select, and multi-select menus can only use plain_text objects,
     * while radio buttons and checkboxes can use mrkdwn text objects.
     *  
     * Maximum length for the text in this field is 75 characters.
     */
    protected TextObject $text;

    /**
     * A unique string value that will be sent to your app when this option is clicked.
     * 
     * Maximum length for this field is 75 characters.
     */
    protected string $value;

    /**
     * A plain_text text object that defines a line of descriptive text shown below the
     * text field beside a single selectable item in a select menu, multi-select menu,
     * checkbox group, radio button group, or overflow menu. 
     * 
     * Maximum length for the text within this field is 75 characters.
     */
    protected ?PlainTextOnlyTextObject $description;

    /**
     * A URL to load in the user's browser when the option is clicked.
     * 
     * The url attribute is only available in overflow menus.
     * 
     * Maximum length for this field is 3000 characters.
     * 
     * If you're using url, you'll still receive an interaction payload and will need to
     * send an acknowledgement response.
     */
    protected ?string $url;

    /**
     * Set the text of the option object.
     */
    public function text(string $text): self
    {
        $this->text = $object = new TextObject($text, 75);

        return $this;
    }

    /**
     * Set the value of the option object.
     */
    public function value(string $text): self
    {
        if(strlen($text) > 75) {
            throw new InvalidArgumentException('Value must be at most 75 characters long.');
        }
        $this->value = $text;

        return $this;
    }

    /**
     * Set the description of the option object.
     */
    public function description(string $label): PlainTextOnlyTextObject
    {
        $this->description = $object = new PlainTextOnlyTextObject($label, 75);

        return $object;
    }

    /**
     * Set the URL of the option object.
     */
    public function url(string $url): self
    {
        if (strlen($url) > 3000) {
            throw new InvalidArgumentException('Maximum length for the url field is 3000 characters.');
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $optionalFields = array_filter([
            'description' => $this->description->toArray(),
            'url'=> $this->url
        ]);

        return array_merge([
            'value' => $this->value,
            'text' => $this->text->toArray(),
        ], $optionalFields);
    }
}
