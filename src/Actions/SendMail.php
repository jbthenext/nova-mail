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
        $mailOptions = json_decode($fields['mail'], true);
        $novaMailTemplateOption = data_get($mailOptions, 'selectedTemplate.id');
        $novaMailTemplate = $novaMailTemplateOption ? NovaMailTemplate::find($novaMailTemplateOption) : null;

        $models->each(function ($model) use ($mailOptions, $novaMailTemplate) {
            $mailable = new Send(
                $model,
                $novaMailTemplate,
                $mailOptions['body'],
                $model->{$model->getEmailField()},
                $mailOptions['subject'],
                null,
                $mailOptions['send_delay_in_minutes']
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
            SendMailField::make('Mail'),
        ];
    }
}
