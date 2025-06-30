# Experiments & A/B Testing User Guide

## Overview

This guide will help you set up and manage A/B tests (experiments) on your website. Experiments allow you to test different versions of your content to see which performs better with your visitors.

## Quick Start

1. **Go to Admin → Experiments** in your dashboard
2. **Click "Create New Experiment"**
3. **Fill in the basic details** (name, description, dates)
4. **Add variations** (different versions to test)
5. **Configure content changes** for each variation
6. **Activate the experiment**
7. **Monitor results** in the analytics dashboard

## Understanding Experiments

### What is an Experiment?
An experiment compares two or more versions of your website content to see which one performs better. For example:
- **Control**: Your current homepage design
- **Variation A**: Same design but with a different headline
- **Variation B**: Same design but with a different call-to-action button

### How It Works
1. **Visitor Assignment**: When someone visits your site, they're randomly assigned to see one version
2. **Content Display**: The system automatically shows the assigned version
3. **Conversion Tracking**: When visitors take actions (like filling forms), these are recorded
4. **Results Analysis**: You can see which version leads to more conversions

## Creating Your First Experiment

### Step 1: Basic Setup

1. Navigate to **Admin → Experiments**
2. Click **"Create New Experiment"**
3. Fill in the following:
   - **Name**: A descriptive name (e.g., "Homepage Headline Test")
   - **Description**: What you're testing and why
   - **Start Date**: When the experiment should begin
   - **End Date**: When the experiment should end (optional)

### Step 2: Add Variations

1. **Control Variation**: This is your current version (usually gets 50% of traffic)
   - Name: "Control" or "Original"
   - Weight: 50 (percentage of visitors who see this version)

2. **Test Variations**: These are your new versions to test
   - Name: "Variation A", "New Headline", etc.
   - Weight: 25, 25 (remaining percentage split between variations)

**Weight Distribution Example:**
- Control: 50% of visitors
- Variation A: 25% of visitors  
- Variation B: 25% of visitors

### Step 3: Configure Content Changes

For each variation, you can modify specific elements on your page:

1. **Click on a variation** to edit it
2. **Click "Add Modification"**
3. **Select the element** you want to change from the dropdown
4. **Choose the modification type**:
   - **Text**: Change text content
   - **HTML**: Replace with custom HTML
   - **CSS Class**: Add/remove CSS classes
   - **Attribute**: Change HTML attributes
   - **Style**: Modify inline styles

### Step 4: Activate the Experiment

1. **Set Status** to "Active"
2. **Save** the experiment
3. **Test** by visiting your site with different browsers/devices

## Available Element Keys

Below is a comprehensive table of all elements you can target in your experiments:

### Hero Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `hero.section` | Main hero section container | CSS Class, Style |
| `hero.title` | Main headline text | Text, HTML |
| `hero.subtitle` | Subtitle below main headline | Text, HTML |
| `hero.description` | Description paragraph | Text, HTML |
| `hero.cta` | Call-to-action button text | Text |
| `hero.media` | Media container (video/image) | CSS Class, Style |
| `hero.video` | Video container | CSS Class, Style |
| `hero.video.iframe` | Video iframe element | Attribute (src) |
| `hero.image` | Hero background image | Attribute (src) |

### Explanation Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `explanation.section` | Explanation section container | CSS Class, Style |
| `explanation.title` | Section title | Text, HTML |
| `explanation.text-1` | First paragraph | Text, HTML |
| `explanation.text-2` | Second paragraph | Text, HTML |
| `explanation.text-3` | Third paragraph | Text, HTML |
| `explanation.text-4` | Fourth paragraph | Text, HTML |
| `explanation.text-5` | Fifth paragraph | Text, HTML |
| `explanation.cta` | Call-to-action button | Text, HTML |

### Consultation Details Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `consultation-details.section` | Section container | CSS Class, Style |
| `consultation-details.box` | Consultation box container | CSS Class, Style |
| `consultation-details.title` | Section title | Text, HTML |
| `consultation-details.item-1` | First list item | Text, HTML |
| `consultation-details.item-2` | Second list item | Text, HTML |
| `consultation-details.item-4` | Fourth list item | Text, HTML |

### Target Audience Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `target-audience.section` | Section container | CSS Class, Style |
| `target-audience.title` | Section title | Text, HTML |
| `target-audience.item-1` | First audience item | CSS Class, Style |
| `target-audience.item-1.text` | First item text | Text, HTML |
| `target-audience.item-2` | Second audience item | CSS Class, Style |
| `target-audience.item-2.text` | Second item text | Text, HTML |
| `target-audience.item-3` | Third audience item | CSS Class, Style |
| `target-audience.item-3.text` | Third item text | Text, HTML |
| `target-audience.item-4` | Fourth audience item | CSS Class, Style |
| `target-audience.item-4.text` | Fourth item text | Text, HTML |

### Why Us Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `why-us.section` | Section container | CSS Class, Style |
| `why-us.title` | Section title | Text, HTML |
| `why-us.item-1` | First reason item | CSS Class, Style |
| `why-us.item-1.title` | First item title | Text, HTML |
| `why-us.item-1.description` | First item description | Text, HTML |
| `why-us.item-2` | Second reason item | CSS Class, Style |
| `why-us.item-2.title` | Second item title | Text, HTML |
| `why-us.item-2.description` | Second item description | Text, HTML |
| `why-us.item-3` | Third reason item | CSS Class, Style |
| `why-us.item-3.title` | Third item title | Text, HTML |
| `why-us.item-3.description` | Third item description | Text, HTML |
| `why-us.item-4` | Fourth reason item | CSS Class, Style |
| `why-us.item-4.title` | Fourth item title | Text, HTML |
| `why-us.item-4.description` | Fourth item description | Text, HTML |

### Testimonials Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `testimonials.container` | Testimonials container | CSS Class, Style |
| `testimonials.card.{id}` | Individual testimonial card | CSS Class, Style |
| `testimonials.{id}.quote` | Testimonial quote text | Text, HTML |
| `testimonials.{id}.author` | Testimonial author name | Text, HTML |
| `testimonials.{id}.position` | Author position/company | Text, HTML |

### Animated Chart Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `animated-chart.roi.label` | ROI metric label | Text |
| `animated-chart.roi.unit` | ROI unit (%, etc.) | Text |
| `animated-chart.conversions.label` | Conversions metric label | Text |
| `animated-chart.conversions.prefix` | Conversions prefix (+/-) | Text |
| `animated-chart.impressions.label` | Impressions metric label | Text |
| `animated-chart.impressions.unit` | Impressions unit (M+, etc.) | Text |
| `animated-chart.ctr.label` | CTR metric label | Text |
| `animated-chart.ctr.unit` | CTR unit (%) | Text |

### Form Section

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `form.section` | Form section container | CSS Class, Style |

### Urgency Banner

| Element Key | Description | Common Modifications |
|-------------|-------------|---------------------|
| `urgency-banner.container` | Banner container | CSS Class, Style |
| `urgency-banner.panel` | Banner panel | CSS Class, Style |
| `urgency-banner.text` | Banner message text | Text, HTML |
| `urgency-banner.dismiss-text` | Dismiss button text | Text |

## Modification Types Explained

### 1. Text Modification
**Use for**: Simple text changes
**Example**: Changing "Get Started" to "Start Free Trial"

### 2. HTML Modification  
**Use for**: Complex content changes, adding elements
**Example**: Adding icons, changing structure, inserting new content

### 3. CSS Class Modification
**Use for**: Styling changes, showing/hiding elements
**Example**: Adding `hidden` class to hide elements, changing colors with utility classes

### 4. Attribute Modification
**Use for**: Changing links, images, form actions
**Example**: Changing button URLs, image sources, form submission endpoints

### 5. Style Modification
**Use for**: Inline styling changes
**Example**: Changing colors, sizes, positioning

## Best Practices

### 1. Test One Thing at a Time
- **Good**: Test just the headline
- **Bad**: Test headline, button color, and layout all at once

### 2. Use Clear, Descriptive Names
- **Good**: "Homepage CTA Button Test"
- **Bad**: "Test 1"

### 3. Set Realistic Timeframes
- **Minimum**: 1-2 weeks for meaningful results
- **Recommended**: 2-4 weeks for most tests
- **Consider**: Traffic volume (more traffic = faster results)

### 4. Monitor Key Metrics
- **Primary**: Conversion rate (form submissions, clicks)
- **Secondary**: Time on page, bounce rate, engagement

### 5. Don't Stop Too Early
- Wait for statistical significance
- Avoid stopping based on early trends
- Consider seasonal factors

## Analyzing Results

### Understanding the Dashboard

1. **Conversion Rate**: Percentage of visitors who completed the goal
2. **Statistical Significance**: How confident we are in the results
3. **Confidence Level**: Usually 95% is considered reliable

### Reading the Results

- **Green arrow up**: This variation is performing better
- **Red arrow down**: This variation is performing worse  
- **No arrow**: No significant difference yet

### When to Declare a Winner

- **Statistical significance**: 95% confidence level or higher
- **Practical significance**: Meaningful improvement in conversion rate
- **Sample size**: Enough visitors to make the result reliable

## Common Experiment Ideas

### Headlines & Copy
- Test different value propositions
- Try benefit-focused vs. feature-focused headlines
- Test different emotional appeals

### Call-to-Action Buttons
- Button text: "Get Started" vs "Start Free Trial"
- Button colors: Blue vs. Green vs. Orange
- Button placement: Above vs. below the fold

### Images & Media
- Different hero images
- Video vs. static image
- Product photos vs. lifestyle images

### Form Optimization
- Number of form fields
- Field labels and placeholders
- Form placement on page

### Page Layout
- Single column vs. two column
- Content order and flow
- White space and spacing

## Troubleshooting

### Experiment Not Showing
1. **Check status**: Is the experiment active?
2. **Check dates**: Is it within the start/end date range?
3. **Clear cache**: Try refreshing in incognito mode
4. **Check targeting**: Verify the element keys are correct

### Results Not Updating
1. **Wait longer**: Results need time to accumulate
2. **Check traffic**: Ensure enough visitors are seeing the experiment
3. **Verify tracking**: Check that conversion events are firing

### Content Not Changing
1. **Check element keys**: Ensure they match exactly
2. **Check modification type**: Verify the right type is selected
3. **Check browser cache**: Try incognito mode or clear cache

## Advanced Features

### Preview Mode
- Use the preview URL to see variations before activating
- Format: `yoursite.com?experiment_id=123&variation_id=456`
- Great for testing and approval workflows

### Custom Events
- Track specific user interactions
- Monitor engagement beyond basic conversions
- Set up custom goals in your analytics

### Traffic Splitting
- Adjust weights to give more traffic to promising variations
- Use 50/50 for initial tests, then optimize based on early results

## Integration with Analytics

### Google Analytics 4
- All experiment events are automatically sent to GA4
- Use the same GA4 property as your main site analytics
- Events include: `view_experiment_variation` and `conversion`