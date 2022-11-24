const {CloudFrontClient, CreateInvalidationCommand, CreateInvalidationResult} = require('@aws-sdk/client-cloudfront');
const {
    CodePipelineClient,
    PutJobFailureResultCommand,
    PutJobSuccessResultCommand,
    JobDetails,
    FailureType
} = require('@aws-sdk/client-codepipeline');

const cloudFront = new CloudFrontClient({});
const codePipeline = new CodePipelineClient({});

exports.handler = async (event, context) => {
    /** @type {JobDetails} **/
    const jobDetails = event['CodePipeline.job'];

    console.log(jobDetails.id, 'Received event');

    let statusCode = 200;
    const response = {};

    try {
        /** @type {CreateInvalidationResult} **/
        const invalidationResponse = await cloudFront.send(new CreateInvalidationCommand({
            DistributionId: jobDetails.data.actionConfiguration.configuration.UserParameters,
            InvalidationBatch: {
                CallerReference: jobDetails.id,
                Paths: {
                    Quantity: 1,
                    Items: [
                        '/index.html'
                    ]
                }
            }
        }));

        console.log(jobDetails.id, 'Created invalidation', invalidationResponse.Invalidation.Id);
        await codePipeline.send(new PutJobSuccessResultCommand({
            jobId: jobDetails.id,
        }));

        response.status = true;
        response.jobId = jobDetails.id;
    } catch (err) {
        console.error(jobDetails.id, err);
        response.error = err;

        await codePipeline.send(new PutJobFailureResultCommand({
            jobId: jobDetails.id,
            failureDetails: {
                type: FailureType.JobFailed,
                message: err.message,
            },
        }));
    }

    return {
        statusCode: statusCode,
        body: JSON.stringify(response),
    };
};
