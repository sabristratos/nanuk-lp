# A/B Testing Documentation

This document explains how to set up and manage A/B tests using the built-in experimentation framework.

## Overview

The A/B testing system allows you to create experiments to test different versions (variations) of your website's components. Each experiment can have multiple variations that are shown to different users, and the system will track which version performs better based on conversion goals.

## Workflow

1.  **Create an Experiment:** Go to the "Experiments" section in the admin panel and create a new experiment.
2.  **Define Variations:** Each experiment needs at least one variation (e.g., a "Control" and a "Variation A"). Set the weight for each to determine the percentage of users who will see it. The total weight must sum to 100.
3.  **Add Changes:** For each variation, add one or more "Changes". This is where you define the actual modifications to apply to your website's components.

## How It Works: Targeting Elements

The system uses a `data-key` attribute to identify which elements on the page can be modified.

### 1. Instrument Your Blade Files

In your Blade component (e.g., `resources/views/components/landing/hero.blade.php`), add a `data-key` attribute to any HTML element you want to make testable. The value of this key should be a unique, descriptive string.

**Example:**

```html
<!-- Target the main headline for text changes -->
<h1 data-key="hero.title">
    This is the default headline.
</h1>

<!-- Target the hero's background div for style changes -->
<div data-key="landing.hero" class="bg-gradient-to-tr ...">
    ...
</div>
```

### 2. Create Changes in the Admin Panel

When editing an experiment's variation, click **"Add Change"**. This will present you with two main fields:

*   **Target Element:** Enter the *exact same key* you used in the `data-key` attribute (e.g., `hero.title`).
*   **Type of Change:** Select the kind of modification you want to make. This will reveal different fields depending on your choice.

### Types of Changes

*   **Change Text / HTML:**
    *   **Use for:** Replacing the text or inner HTML of an element.
    *   **Payload:** Provides a multi-language text editor to enter the new content.

*   **Change Style:**
    *   **Use for:** Modifying the CSS properties of an element.
    *   **Payload:**
        *   **CSS Property:** Choose between `Text Color` and `Background Color`.
        *   **Value:** A color picker to select the new color.
    *   **Note:** When changing `Background Color`, the system will automatically try to remove existing Tailwind `bg-*` classes to ensure the new color is applied.

*   **Change Visibility:**
    *   **Use for:** Showing or hiding an element.
    *   **Payload:** A dropdown to select `Visible` or `Hidden`.

*   **Add Custom CSS Classes:**
    *   **Use for:** Appending one or more utility classes (e.g., from Tailwind CSS) to an element.
    *   **Payload:** A text area to enter the classes, separated by spaces.

*   **Modify Component Layout:**
    *   **Use for:** Completely replacing all classes on the experiment's root element. This is useful for testing entirely different layouts.
    *   **Payload:** A text area to enter the new list of classes.

By combining these change types, you can create complex variations to test any aspect of your components.

## How to Configure Component Styles and Layout

Beyond changing content, you can also test variations of a component's style and structure using **Component Configurations**. This system uses `data-element-key` attributes to target specific HTML elements within a component.

### 1. Identify the Target Element in your Blade files

In your Blade component, add a `data-element-key` attribute to the HTML element you want to configure.

**Example:**

To test the background color of the hero section, you would add `data-element-key="hero.section"` to the `<div>` that controls the background:

```html
<div data-element-key="hero.section" class="absolute inset-x-0 ... bg-gradient-to-tr from-some-color to-another-color">
    ...
</div>
```

### 2. Create a Component Configuration in the Admin Panel

When editing an experiment's variation in the admin panel:

1.  Click **"Add Component Configuration"**.
2.  In the **"Element Key"** field, enter the *same key* you used in the `data-element-key` attribute (e.g., `hero.section`).
3.  **Element Type**: This defines the type of configuration to apply.
    *   `Layout`: Replaces the component's root element's CSS classes with the ones you provide. This is useful for testing completely different Tailwind CSS layouts.
    *   `Background Color`: Removes any existing `bg-`, `from-`, or `to-` classes from the target element and applies the chosen background color. This is ideal for testing solid background colors on elements that might have gradients or default background colors from Tailwind.
    *   `Position`: Applies `left` and `top` pixel values to the element. Note: this will also set `position: absolute` on the element.
4.  **Configuration**: Enter the values for your chosen configuration type (e.g., a hex color code for "Background Color" or a list of CSS classes for "Layout").

The `experimentManager` script will then find the targeted element and apply the specified configuration.

### Example in `hero.blade.php`

The hero component is already set up for A/B testing. Here are the keys you can use:
*   `data-content-key="hero.title"`: The main headline.
*   `data-content-key="hero.subtitle"`: The paragraph below the main headline.
*   `data-content-key="hero.description"`: The second paragraph of text.
*   `data-content-key="hero.cta"`: The text of the main call-to-action button.
*   `data-element-key="hero.section"`: The main background element of the hero section.

By following these steps, you can run experiments that change text, HTML, and even styles on any instrumented component. 