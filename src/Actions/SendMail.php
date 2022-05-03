<?php

namespace KirschbaumDevelopment\NovaMail\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use KirschbaumDevelopment\NovaMail\Mail\Send;
use KirschbaumDevelopment\NovaMail\Models\NovaMailTemplate;
use KirschbaumDevelopment\NovaMail\SendMail as SendMailField;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;

class SendMail extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //$novaMailTemplate = $novaMailTemplateOption ? NovaMailTemplate::find($novaMailTemplateOption) : null;
        $novaMailTemplate = null;

        $models->each(function ($model) use ($fields, $novaMailTemplate) {
            $mailable = new Send(
                $model,
                $novaMailTemplate,
                $fields['body'],
                $model->{$model->getEmailField()},
                $fields['subject'],
                null,
                $fields['send_delay_in_minutes']
            );
            $mailable->deliver();
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Subject')->rules('required'),
            Number::make('Send Delay (in minutes)')->min(0)->max(120)->step(1),
            Textarea::make('Body')->rules('required')
        ];
    }
}
