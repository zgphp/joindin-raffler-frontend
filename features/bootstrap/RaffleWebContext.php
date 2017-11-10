<?php

declare(strict_types=1);

use App\Entity\JoindInUser;
use Behat\MinkExtension\Context\MinkContext;
use PhpSpec\Exception\Example\PendingException;
use Symfony\Component\HttpKernel\KernelInterface;

class RaffleWebContext extends MinkContext
{
    use FixturesTrait;
    use HelperTrait;

    /** @var KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When organizer picks to raffle meetups: :eventIdList
     */
    public function organizerPicksToRaffleMeetups(string $eventIdList)
    {
        $this->visit('/web');

        foreach (explode(',', $eventIdList) as $eventId) {
            $this->checkOption($eventId);
        }

        $this->pressButton('New raffle');
    }

    /**
     * @When I pick a winner
     * @When I pick another winner
     */
    public function wePick()
    {
        $this->pressButton('RAFFLE!');
    }

    /**
     * @When I confirm him or her as a winner
     */
    public function iConfirmHimOrHerAsAWinner()
    {
        $this->pressButton('WINNER!');
    }

    /**
     * @When I mark him or her as a no show
     */
    public function iMarkHimOrHerAsANoShow()
    {
        $this->pressButton('no show :(');
    }

    /**
     * @When user :user wins
     */
    public function userWins(JoindInUser $user)
    {
        throw new PendingException();
    }

    /**
     * @When user :user is no show
     */
    public function userIsNoShow(JoindInUser $user)
    {
        throw new PendingException();
    }

    /**
     * @Then there should be :count events on the raffle
     */
    public function thereShouldBeEventsOnTheRaffle(int $count)
    {
        $this->assertPageContainsText('Raffling '.$count.' events!');
    }

    /**
     * @Then there should be :count comments on the raffle
     */
    public function thereShouldBeCommentsOnTheRaffle(int $count)
    {
        throw new PendingException();
    }

    /**
     * @Then we should get one of :userNames as a winner
     */
    public function weShouldGetOneOfAsAWinner(string $userNames)
    {
        $users = $this->loadMultipleUsers($userNames);

        foreach ($users as $user) {
            try {
                $this->assertPageContainsText('YEAHHHHH '.$user->getDisplayName());

                // Name was found, all ok.
                return;
            } catch (\Exception $exception) {
                // if the current name wasnt found, continue searching for other names
                continue;
            }
        }

        throw new Exception('None of the expected names was found for a winner');
    }

    /**
     * @Then we should get back one of the members that left feedback
     */
    public function weShouldGetBackOneOfTheMembersThatLeftFeedback()
    {
        throw new PendingException();
    }

    /**
     * @Then :user user should be :count times in the list
     */
    public function userShouldBeTimesInTheList(JoindInUser $user, int $count)
    {
        throw new PendingException();
    }

    /**
     * @Then we should get :user as a winner
     */
    public function weShouldGetAsAWinner(JoindInUser $user)
    {
        throw new PendingException();
    }

    /**
     * @Then we should have :count eligible comment for next prize
     * @Then we should have :count eligible comments for next prize
     */
    public function weShouldHaveEligibleCommentForNextPrize(int $count)
    {
        $this->assertPageContainsText('There are '.$count.' eligible!');
    }

    /**
     * @Then we cannot continue raffling
     */
    public function weCannotContinueRaffling()
    {
        $this->assertPageNotContainsText('RAFFLE!');
    }

    protected function getService(string $name)
    {
        return $this->kernel->getContainer()->get($name);
    }
}
